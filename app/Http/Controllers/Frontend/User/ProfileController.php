<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
// DELETE LATER
//use Illuminate\Support\Facades\Log;

/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function update(UpdateProfileRequest $request)
    {
      //  Log::debug('From form: ' . $request->subscr_mask);
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name', 'email', 'avatar_type', 'avatar_location', 'timezone'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );
        $subscr = isset($request->subscr) ? $request->subscr : array(); // empty arry if not present in form request
        $request->user()->set_subscriptions_to_array($subscr);
        if ($request->user()->hasrole('student')) {
            $request->user()->studentid = $request->studentid;
            $request->user()->save();
        }
        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();
            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }
        if (empty($subscr)) {
          return redirect()->route('frontend.user.account')->withFlashWarning('You may want to subscribe to at least one matter. Just to let you know.');
        }
        else {
          return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
        }

    }

    public function intro_update (\Illuminate\Http\Request $request) { // I believe this is secure without further checks
        if (strlen($request->interests) < 10) {
            return back()->withFlashWarning('A bit more text please.');
        }
        $user = $request->user();
        $user->interests = $request->interests;
        $user->save();
        return redirect()->route('frontend.person.show', $user->id)->withFlashSuccess('Personal introduction saved.');
    }
}
