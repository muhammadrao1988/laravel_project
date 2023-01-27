<?php
namespace App\View\Composers;

use Illuminate\View\View;
use Jenssegers\Agent\Agent;


class ProfileComposer
{
    /**
     * The user repository implementation.
     *
     * @var \App\Repositories\UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  \App\Repositories\UserRepository  $users
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $auth_user = \auth()->guard('web')->user();
        $agent = new Agent();
        $isMobile = false;
        if($agent->isMobile() || $agent->isTablet()){
            $isMobile = true;
        }
        if(empty($auth_user->id)){
            $auth_user = false;
        }
        $view->with('auth_user_web', $auth_user)->with('isMobile',$isMobile);
    }
}