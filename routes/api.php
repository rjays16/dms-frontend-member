<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PCRMemberController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UsersImportController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AdvertismentController;
use App\Http\Controllers\ChangeProfilePictureController;
use App\Http\Controllers\MiniSessionController;
use App\Http\Controllers\RelatedEventController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ConventionContestController;
use App\Http\Controllers\PlenaryVideoController;
use App\Http\Controllers\LobbyVideoController;
use App\Http\Controllers\MainBannerController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HeaderLogoController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\AttendeesController;
use App\Http\Controllers\TagController;
use App\Mail\SendEmailMailable;
use App\Jobs\SendNotificationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Services\IdeapayService;

if (App::environment('production')) {
    URL::forceScheme('https');
}

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:sanctum')->get('conv/user', function (Request $request) {
//     return $request->user();
// });

Auth::routes(['verify' => true, 'reset' => false]);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/login');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::resource('members', PCRMemberController::class);


// Route::post('/register', [RegistrationController::class, 'register']);
// Route::post('/upload/pcrid/{id}', [RegistrationController::class, 'uploadPrcId']);
// Route::post('/auth/conv/login', [AuthController::class, 'conventionLogin']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/createuser', [AuthController::class, 'usercreate']);
Route::post('/createroomuser', [AuthController::class, 'roomsUserCreate']);
Route::get('/checkuser', [AuthController::class, 'memberFilter']);
Route::get('/registrationtype', [FieldController::class, 'getAllRegistrationType']);
Route::get('/membershiptype', [FieldController::class, 'getMembershipTypes']);
Route::get('/nonMembertypes', [FieldController::class, 'getAllNonMemberType']);
Route::get('/chaptertypes', [FieldController::class, 'getAllChapters']);
Route::post('/user/uploadpic/{id}', [AuthController::class, 'uploadProfPic']);
Route::put('/pcr/member/conventiontag/{id}', [PCRMemberController::class, 'tagForConvention']);
Route::post('/admin/import', [UsersImportController::class, 'store']);
Route::post('/admin/lifetime/import', [UsersImportController::class, 'storeLifeMembers']);
// Route::delete('/auth/logout/admin', [AuthController::class, 'logoutAdmin']);
Route::put('/registration/decline/{id}', [RegistrationController::class, 'declineRegistration']);
Route::put('/member/decline/{id}', [PCRMemberController::class, 'declineMember']);
Route::get('/minisession/rooms/schedule', [MiniSessionController::class, 'getAllRoomsByDate']);
Route::get('/convention/profile/forregistration/{id}', [RegistrationController::class, 'getConventionProfile']);
Route::get('/getdatetime', [AuthController::class, 'getDateTime']);
Route::get('/getallmembers', [PCRMemberController::class, 'getAllApprovedNoPagination']);

Route::get('/banner', [MainBannerController::class, 'getAll']);
Route::get('/footer', [FooterController::class, 'getAll']);
Route::get('/headerlogo', [HeaderLogoController::class, 'getAll']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/test', [AuthController::class, 'test']);
    Route::post('/bulkemail', [AuthController::class, 'bulkEmail']);
    // Route::delete('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/pcr/member/{id}', [PCRMemberController::class, 'getMember']);
    Route::get('/pcr/search', [PCRMemberController::class, 'searchMember']);
    Route::get('/pcr/memberemail/', [PCRMemberController::class, 'getMemberViaEmail']);
    Route::put('/pcr/member/{id}', [PCRMemberController::class, 'updateMember']);
    Route::post('/pcr/upload/deposit/{id}', [PCRMemberController::class, 'uploadDepositSlip']);
    // Sponsors APIs
    Route::post('/sponsors/create', [SponsorController::class, 'register']);
    Route::post('/sponsors/draft/upload/{id}/{file_type}', [SponsorController::class, 'uploadToSample']);
    Route::put('/sponsors/update/{id}', [SponsorController::class, 'updateInfo']);
    Route::get('/sponsors/samplebooth/{id}', [SponsorController::class, 'viewSampleBooth']);
    Route::put('/sponsors/approvebooth/{id}', [SponsorController::class, 'approveBooth']);
    Route::put('/sponsors/declinebooth/{id}', [SponsorController::class, 'declineSampleBooth']);
    Route::get('/sponsors/pendingbooth', [SponsorController::class, 'getAllPendingSponsorBooth']);
    Route::get('/sponsors/all', [SponsorController::class, 'getAllSponsors']);
    //sponsors added by abing
    Route::get('/sponsors/{id}', [SponsorController::class, 'getSponsorProfileBooth']);
    Route::get('/sponsors/resource/{id}', [SponsorController::class, 'getAllSponsorResources']);
    Route::get('/sponsors/product/{id}', [SponsorController::class, 'getAllSponsorProducts']);
    // ADMIN APIs
    Route::get('/convention/profile/{id}', [RegistrationController::class, 'getConventionProfile']);
    Route::get('/convention/search', [RegistrationController::class, 'searchRegisteration']);

    //Announcement APIs
    Route::post('/pcr/announcement', [AnnouncementController::class, 'create']);
    Route::get('/pcr/announcement/all', [AnnouncementController::class, 'getAnnouncements']);
    Route::put('/pcr/announcement/update/{id}', [AnnouncementController::class, 'updateAnnoucement']);
    Route::post('/pcr/announcement/updatefile/{id}', [AnnouncementController::class, 'updateAnnouncementFile']);
    Route::delete('/pcr/announcement/delete/{id}', [AnnouncementController::class, 'deleteAnnoucement']);
    Route::get('/pcr/announcement/details/{id}', [AnnouncementController::class, 'getAnnouncementById']);

    //Advertisment APIs
    Route::post('/pcr/ads', [AdvertismentController::class, 'create']);
    Route::get('/pcr/ads/all', [AdvertismentController::class, 'getAds']);
    Route::put('/pcr/ads/update/{id}', [AdvertismentController::class, 'updateAds']);
    Route::post('/pcr/ads/updatefile/{id}', [AdvertismentController::class, 'updateAdsFile']);
    Route::delete('/pcr/ads/delete/{id}', [AdvertismentController::class, 'deleteAds']);
    Route::get('/pcr/ads/details/{id}', [AdvertismentController::class, 'getAdById']);

    //Mini Sessions
    Route::post('/minisession/room', [MiniSessionController::class, 'createRoom']);
    Route::post('/minisession/create', [MiniSessionController::class, 'onePageCreate']);
    Route::put('/minisession/room/edit/{id}', [MiniSessionController::class, 'updateRoom']);
    Route::get('/minisession/rooms', [MiniSessionController::class, 'getAllRooms']);
    Route::get('/minisession/rooms/sponsor/{id}', [MiniSessionController::class, 'getAllRoomsBySponsor']);
    Route::post('/minisession/session/{id}', [MiniSessionController::class, 'ScheduleSession']);
    Route::get('/minisession/session/{id}', [MiniSessionController::class, 'showRoom']);
    Route::delete('/minisession/session/{id}', [MiniSessionController::class, 'delete']);
    Route::post('/minisession/upload/session/{id}', [MiniSessionController::class, 'uploadMaterial']);
    Route::get('/minisession/materials/session/{id}', [MiniSessionController::class, 'getSessionMaterials']);
    Route::delete('/minisession/materials/{id}', [MiniSessionController::class, 'deleteMaterial']);
    Route::post('/minisession/attendee/register', [MiniSessionController::class, 'registerAttendee']);
    Route::get('/minisession/attendee/get/session/{id}', [MiniSessionController::class, 'getSessionAttendee']);

    //Related Events
    // Route::post('/events/create', [RelatedEventController::class, 'createRoom']);
    Route::post('/events/sitelink/edit/{id}', [RelatedEventController::class, 'updateEvent']);
    Route::get('/events/sitelinks', [RelatedEventController::class, 'getAllEvents']);
    // Route::post('/minisession/session/{id}', [RelatedEventController::class, 'ScheduleSession']);

    //Election APIs
    Route::get('/election/members/{id}', [ElectionController::class, 'getElectionPeriodNominees']);
    Route::post('/election/create', [ElectionController::class, 'create']);
    Route::get('/election/search', [ElectionController::class, 'searchNominees']);
    Route::get('/election/nominated/search', [ElectionController::class, 'searchNominated']);
    Route::get('/election/checkActive', [ElectionController::class, 'checkActiveElection']);
    Route::get('/election/nominees/{id}', [ElectionController::class, 'getNominees']);
    Route::get('/election/history/{id}', [ElectionController::class, 'getElectionPeriodDetails']);
    Route::put('/election/close/{id}', [ElectionController::class, 'close']);
    Route::get('/election/nominee/{id}', [ElectionController::class, 'getMember']);
    Route::post('/election/nominate', [ElectionController::class, 'nominate']);
    Route::get('/election/checkNominee/{period}/{member}', [ElectionController::class, 'checkNominee']);
    Route::put('/election/updateNominated/{id}', [ElectionController::class, 'updateNominated']);
    Route::delete('/election/deleteNominee/{id}', [ElectionController::class, 'deleteNominee']);
    Route::put('/election/selectedNominee', [ElectionController::class, 'getSelectedNominees']);
    Route::put('/election/vote', [ElectionController::class, 'vote']);
    Route::get('/election/checkVoteStatus/{id}/{election}', [ElectionController::class, 'checkVoteStatus']);
    Route::get('/election/nomineeDetails/{id}', [ElectionController::class, 'getNomineeDetails']);

    Route::get('/election/{type}/{user}', [ElectionController::class, 'getElectionPeriod']);


    //Photo Contest API

    // Research Poster
    Route::get('/contest/item/getResearchPoster', [ConventionContestController::class, 'getPosterResearchEntries']);
    Route::post('/contest/item/create/poster', [ConventionContestController::class, 'createPosterResearch']);
    Route::delete('/contest/item/poster/{id}', [ConventionContestController::class, 'deletePosterResearch']);

    // Oral Research
    Route::get('/contest/item/getOralResearch', [ConventionContestController::class, 'getOralResearchEntries']);
    Route::post('/contest/item/create/oral', [ConventionContestController::class, 'createOralResearch']);
    Route::delete('/contest/item/oral/{id}', [ConventionContestController::class, 'deleteOralResearch']);

    // Photo Contest
    Route::get('/contest/item/getPhotography', [ConventionContestController::class, 'getPhotographyEntries']);
    Route::post('/contest/item/create/photo', [ConventionContestController::class, 'createPhotography']);

    Route::post('/contest/item/create', [ConventionContestController::class, 'store']);
    Route::post('/contest/item/upload/{id}/{file_type}', [ConventionContestController::class, 'uploadFiles']);


    Route::post('/contest/editResearchPosterEntry', [ConventionContestController::class, 'editResearchPosterEntry']);
    Route::post('/contest/editOralResearchEntry', [ConventionContestController::class, 'editOralResearchEntry']);
    Route::post('/contest/editPhotoContestEntry', [ConventionContestController::class, 'editPhotoContestEntry']);

    Route::post('/contest/setRank', [ConventionContestController::class, 'setRank']);

    /// Change Profile Picture
    Route::post('/changeProfilePic', [ChangeProfilePictureController::class, 'changeProfilePic']);

    // Plenary Hall Video and Chat
    Route::post('/plenary', [PlenaryVideoController::class, 'store']);
    Route::get('/plenary/{id}', [PlenaryVideoController::class, 'getPlenary']);
    Route::put('/plenary/update/{id}', [PlenaryVideoController::class, 'update']);
    Route::delete('/plenary/delete/{id}', [PlenaryVideoController::class, 'destroy']);
    Route::get('/plenary', [PlenaryVideoController::class, 'getAll']);

    // Lobby Video
    Route::post('/lobby', [LobbyVideoController::class, 'store']);
    Route::get('/lobby/{id}', [LobbyVideoController::class, 'getLobby']);
    Route::put('/lobby/update/{id}', [LobbyVideoController::class, 'update']);
    Route::delete('/lobby/delete/{id}', [LobbyVideoController::class, 'destroy']);
    Route::get('/lobby', [LobbyVideoController::class, 'getAll']);

    // Main Banner
    Route::post('/banner', [MainBannerController::class, 'createBanner']);
    Route::get('/banner/{id}', [MainBannerController::class, 'getMainBanner']);
    Route::put('/banner/update/{id}', [MainBannerController::class, 'update']);
    Route::delete('/banner/delete/{id}', [MainBannerController::class, 'destroy']);   
    Route::post('/banner/upload', [MainBannerController::class, 'changeBanner']);

    // Footer
    Route::post('/footer', [FooterController::class, 'store']);
    Route::get('/footer/{id}', [FooterController::class, 'getFooter']);
    Route::put('/footer/update/{id}', [FooterController::class, 'update']);
    Route::delete('/footer/delete/{id}', [FooterController::class, 'destroy']);
    Route::post('/footer/upload', [FooterController::class, 'changeFooterImage']);

    // Header Logo
    Route::post('/headerlogo', [HeaderLogoController::class, 'store']);
    Route::get('/headerlogo/{id}', [HeaderLogoController::class, 'getLogo']);
    Route::put('/headerlogo/update/{id}', [HeaderLogoController::class, 'update']);
    Route::delete('/headerlogo/delete/{id}', [HeaderLogoController::class, 'destroy']);
    Route::post('/headerlogo/upload', [HeaderLogoController::class, 'changeLogo']);

    // Room APIs
    Route::post('/room', [RoomController::class, 'store']);
    Route::get('/room/{id}', [RoomController::class, 'getRoom']);
    Route::put('/room/update/{id}', [RoomController::class, 'update']);
    Route::delete('/room/delete/{id}', [RoomController::class, 'destroy']);
    Route::get('/room', [RoomController::class, 'getAll']);

    // Tag APIs
    Route::post('/tags', [TagController::class, 'store']);
    Route::get('/tags/{id}', [TagController::class, 'getRoom']);
    Route::put('/tags/update/{id}', [TagController::class, 'update']);
    Route::delete('/tags/delete/{id}', [TagController::class, 'destroy']);
    Route::get('/all/tags', [TagController::class, 'getAll']);

    // Session APIs
    Route::post('/session', [SessionController::class, 'createSession']);
    Route::get('/session/{id}', [SessionController::class, 'getSession']);
    Route::put('/session/update/{id}', [SessionController::class, 'update']);
    Route::delete('/session/delete/{id}', [SessionController::class, 'destroy']);
    Route::get('/session', [SessionController::class, 'getAll']);
    Route::get('/session/attendees/{id}', [SessionController::class, 'getAllAttendees']);
    Route::get('/session/attendees/all/{id}', [SessionController::class, 'getAllAttendeesNoPagination']);
    Route::get('/session/attendees/search/{id}/{key}', [SessionController::class, 'search']);
    Route::post('/session/attend', [AttendeesController::class, 'store']);
    Route::delete('/session/attendee/{id}', [AttendeesController::class, 'destroy']);
    Route::get('/session/user/{id}', [SessionController::class, 'getSessionsByUser']);
    Route::post('/session/group/attend', [AttendeesController::class, 'storeGroup']);


    //User
    Route::get('/attendees/{id}', [AuthController::class, 'getUser']);
    Route::get('/attendees', [AttendeesController::class, 'index']);
    Route::get('/attendees/search/{i}', [AttendeesController::class, 'search']);
    Route::put('/attendees/update/{id}', [AuthController::class, 'updateUser']);
    Route::delete('/attendees/delete/{id}', [AuthController::class, 'destroyUser']);
});
