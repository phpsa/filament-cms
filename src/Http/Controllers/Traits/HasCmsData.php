<?php

namespace Phpsa\FilamentCms\Http\Controllers\Traits;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Phpsa\FilamentCms\Models\CmsContentPages;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait HasCmsData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View
     */
    public function __invoke(Request $request, string $page)
    {
        return $this->show($request, $page);
    }

    public function resolveRouteBinding(string $page): CmsContentPages
    {
        return CmsContentPages::whereSlug($page)->whereNamespace($this->resource)->firstOrFail();
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, string $page)
    {
        $page = $this->resolveRouteBinding($page);

        $this->authorisePage($page);

        $views = [$this->view, 'filament-cms::' . $this->view];

        $data = $this->viewData($page);

        return request()->wantsJson() ? response()->json($data)
        : View::first(
            $views,
            $data
        );
    }


    protected function viewData(CmsContentPages $page): array
    {
        return [
            'page'  => $page,
        ];
    }


    protected function authorisePage(CmsContentPages $page): void
    {
        abort_unless($page->namespace === $this->resource, 404);

        /** @var \Phpsa\FilamentCms\Enum\StatusEnum $status */
        $status = config('filament-cms.statusEnum');

        $this->authoriseViaPassword($page);

        $isRoleProtected = $page->status === $status::roleProtected();
        $isAuthProtected = $page->status === $status::authProtected() || $isRoleProtected;

        throw_if(
            $isAuthProtected && ! auth()->check(),
            AuthenticationException::class
        );

        /** @var User $user */
        $user = auth()->user();

        throw_if(
            $isRoleProtected && ! $user->hasAnyRole($page->security['roles']),
            UnauthorizedException::forRolesOrPermissions((array)$page->security['roles'])
        );
    }

    protected function authoriseViaPassword(CmsContentPages $page): void
    {

        $status = config('filament-cms.statusEnum');
        $isPasswordProtected = $status::passwordProtected();

        if ($page->status !== $isPasswordProtected) {
            return;
        }
        $key = 'cms_id_' . $page->id . '_password';
        $password = request()->input('password') ?? request()->header('password');
        if ($password) {
            session([$key => encrypt($password)]);
        }

        $userPassword = session()->has($key) ? decrypt(session($key)) : null;

        if ($userPassword !== $page->security['password']) {
            throw ValidationException::withMessages(['password' => 'Incorrect Password supplied for this resource']);
        }
    }
}
