import AdminLayout from "./components/layouts/pcr_admin/AdminLayout.vue";
import AdminHome from "./components/modules/pcr_admin/Home.vue";

import AdminLogin from "./components/modules/pcr_admin/Login.vue";

import VoucherCodesList from "./components/modules/voucher-codes/VoucherCodesList.vue";
import NotFound from "./components/modules/dashboard/404.vue";

import Announcements from "./components/modules/pcr_admin/announcements/Announcements.vue";
import AnnouncementsAdd from "./components/modules/pcr_admin/announcements/AddAnnouncements.vue";
import AnnouncementsEdit from "./components/modules/pcr_admin/announcements/EditAnnouncements.vue";

import MiniSeminars from "./components/modules/pcr_admin/miniseminars/MiniSeminars.vue";
import MiniSeminarsAdd from "./components/modules/pcr_admin/miniseminars/MiniSeminarsAdd.vue";
import MiniSeminarsUpdate from "./components/modules/pcr_admin/miniseminars/MiniSeminarsUpdate.vue";
import MiniSeminarsStart from "./components/modules/pcr_admin/miniseminars/MiniSeminarsStart.vue";

import Slider from "./components/modules/pcr_admin/slider/Slides.vue";
import SlidesAdd from "./components/modules/pcr_admin/slider/AddSlide.vue";
import SlidesEdit from "./components/modules/pcr_admin/slider/EditSlide.vue";

import RelatedEvents from "./components/modules/pcr_admin/home/RelatedEvents.vue";
import RelatedEventsEdit from "./components/modules/pcr_admin/home/RelatedEventsEdit.vue";

import MainBanner from "./components/modules/pcr_admin/home/MainBanner.vue";
import LoginBanner from "./components/modules/pcr_admin/banner/LoginBanner.vue";

import HeaderSettings from "./components/modules/pcr_admin/site/Header.vue";

import FooterSettings from "./components/modules/pcr_admin/site/Footer.vue";

import LobbySettings from "./components/modules/pcr_admin/lobby/LobbySettings.vue";

import PlenarySettings from "./components/modules/pcr_admin/plenary/PlenarySettings.vue";

import MembersApplicant from "./components/modules/pcr_admin/members/Applicant.vue";
import MembersForProcessing from "./components/modules/pcr_admin/members/ForProcessing.vue";
import MembersActive from "./components/modules/pcr_admin/members/Active.vue";
import MembersInactive from "./components/modules/pcr_admin/members/Inactive.vue";
import MembersDeclined from "./components/modules/pcr_admin/members/Declined.vue";

import SponsorsList from "./components/modules/pcr_admin/sponsors/Sponsors.vue";
import SponsorsAdd from "./components/modules/pcr_admin/sponsors/AddSponsors.vue";
import SponsorsEdit from "./components/modules/pcr_admin/sponsors/EditSponsors.vue";
import SponsorsBoothChanges from "./components/modules/pcr_admin/sponsors/BoothChanges.vue";

import SponsorsBoothPlatinum from "./components/modules/pcr_admin/sponsors/preview/PreviewPlatinum.vue";
import SponsorsBoothGold from "./components/modules/pcr_admin/sponsors/preview/PreviewGold.vue";
import SponsorsBoothSilver from "./components/modules/pcr_admin/sponsors/preview/PreviewSilver.vue";
import SponsorsBoothBronze from "./components/modules/pcr_admin/sponsors/preview/PreviewBronze.vue";
import SponsorsBoothSupporter from "./components/modules/pcr_admin/sponsors/preview/PreviewSupporter.vue";

import ElectionActive from "./components/modules/pcr_admin/election/Active.vue";
import ElectionHistory from "./components/modules/pcr_admin/election/History.vue";
import ElectionAddNominees from "./components/modules/pcr_admin/election/AddNominees.vue";

import PosterResearch from "./components/modules/pcr_admin/contest/PosterResearch.vue";
import OralResearch from "./components/modules/pcr_admin/contest/oral/OralResearch.vue";
import Photography from "./components/modules/pcr_admin/contest/photography/Photography.vue";

import Sessions from "./components/modules/pcr_admin/sessions/Sessions.vue";
import SessionsAdd from "./components/modules/pcr_admin/sessions/SessionsAdd.vue";
import SessionsUpdate from "./components/modules/pcr_admin/sessions/SessionsUpdate.vue";
import SessionsParticipants from "./components/modules/pcr_admin/sessions/SessionParticipants.vue";

import Users from "./components/modules/pcr_admin/users/Users.vue";
import UsersAdd from "./components/modules/pcr_admin/users/UsersAdd.vue";
import UsersUpdate from "./components/modules/pcr_admin/users/UsersUpdate.vue";

import Rooms from "./components/modules/pcr_admin/rooms/Rooms.vue";
import RoomsAdd from "./components/modules/pcr_admin/rooms/RoomsAdd.vue";
import RoomsUpdate from "./components/modules/pcr_admin/rooms/RoomsUpdate.vue";

import Groups from "./components/modules/pcr_admin/groups/Groups.vue";
import GroupsAdd from "./components/modules/pcr_admin/groups/GroupsAdd.vue";
import GroupsUpdate from "./components/modules/pcr_admin/groups/GroupsUpdate.vue";
import GroupsUsers from "./components/modules/pcr_admin/groups/UserTagList.vue";

import CollectionFeesList from "./components/modules/pcr_admin/collection-fees/List.vue";
import FiscalPeriodActive from "./components/modules/pcr_admin/fiscal-period/Active.vue";

import CollectionFeesListFiscal from "./components/modules/pcr_admin/collection-fees/ListFiscal.vue";
import CollectionFeesListOther from "./components/modules/pcr_admin/collection-fees/ListOther.vue";

import PaymentLedgersList from "./components/modules/pcr_admin/payment-ledgers/List.vue";

// Routes
const routes = [
    {
        path: "/admin",
        component: AdminLayout,
        meta: { requiresAuth: false },
        beforeEnter: (to, from, next) => {
            document.body.className += " admin-main sidebar-mini";
            next();
        },
        activate: function() {
            this.$nextTick(function() {
                // => 'DOM loaded and ready'
                alert("test");
            });
        },
        children: [
            {
                path: "dashboard",
                name: "dashboard-first",
                component: AdminHome
            },
            {
                path: "login",
                name: "login-admin",
                component: AdminLogin
            },
            {
                path: "vouchercodes",
                name: "voucher-codes",
                component: VoucherCodesList
            },
            {
                path: "announcements",
                name: "announcements",
                component: Announcements
            },
            {
                path: "announcements/add",
                name: "announcements-add",
                component: AnnouncementsAdd
            },
            {
                path: "announcements/edit",
                name: "announcements-edit",
                component: AnnouncementsEdit
            },
            {
                path: "slider",
                name: "slider",
                component: Slider
            },
            {
                path: "slider/add",
                name: "slider-add",
                component: SlidesAdd
            },
            ,
            {
                path: "slider/edit",
                name: "slider-edit",
                component: SlidesEdit
            },
            {
                path: "banner/home",
                name: "banner-home",
                component: MainBanner
            },
            {
                path: "banner/login",
                name: "banner-login",
                component: LoginBanner
            },
            {
                path: "site/header",
                name: "site-header",
                component: HeaderSettings
            },
            {
                path: "site/footer",
                name: "site-footer",
                component: FooterSettings
            },
            {
                path: "home/related-events",
                name: "related-events",
                component: RelatedEvents
            },
            {
                path: "home/related-events/edit",
                name: "edit-related-events",
                component: RelatedEventsEdit
            },
            {
                path: "lobby/lobby-settings",
                name: "lobby-settings",
                component: LobbySettings
            },
            {
                path: "plenary/plenary-settings",
                name: "plenary-settings",
                component: PlenarySettings
            },
            {
                path: "members/applicant",
                name: "members-applicant",
                component: MembersApplicant
            },
            {
                path: "members/for-processing",
                name: "members-for-processing",
                component: MembersForProcessing
            },
            {
                path: "members/active",
                name: "members-active",
                component: MembersActive
            },
            {
                path: "members/inactive",
                name: "members-inactive",
                component: MembersInactive
            },
            {
                path: "members/declined",
                name: "members-declined",
                component: MembersDeclined
            },
            {
                path: "sponsors",
                name: "sponsors-list",
                component: SponsorsList
            },
            {
                path: "mini-sessions",
                name: "mini-sessions",
                component: MiniSeminars
            },
            {
                path: "mini-sessions-start",
                name: "mini-sessions-start",
                component: MiniSeminarsStart
            },
            {
                path: "mini-sessions/add",
                name: "mini-sessions-add",
                component: MiniSeminarsAdd
            },
            {
                path: "mini-sessions/update",
                name: "mini-sessions-update",
                component: MiniSeminarsUpdate
            },
            {
                path: "sessions",
                name: "sessions",
                component: Sessions
            },
            {
                path: "sessions/add",
                name: "sessions-add",
                component: SessionsAdd
            },
            {
                path: "sessions/update",
                name: "sessions-update",
                component: SessionsUpdate
            },
            {
                path: "sessions/participants",
                name: "sessions-participants",
                component: SessionsParticipants
            },
            {
                path: "rooms",
                name: "rooms",
                component: Rooms
            },
            {
                path: "rooms/add",
                name: "rooms-add",
                component: RoomsAdd
            },
            {
                path: "rooms/update",
                name: "rooms-update",
                component: RoomsUpdate
            },
            {
                path: "users",
                name: "users",
                component: Users
            },
            {
                path: "users/add",
                name: "users-add",
                component: UsersAdd
            },
            {
                path: "users/update",
                name: "users-update",
                component: UsersUpdate
            },
            {
                path: "tags",
                name: "tags",
                component: Groups
            },
            {
                path: "tags/users",
                name: "tags-users",
                component: GroupsUsers
            },
            {
                path: "tags/add",
                name: "tags-add",
                component: GroupsAdd
            },
            {
                path: "tags/update",
                name: "tags-update",
                component: GroupsUpdate
            },
            {
                path: "sponsors/add",
                name: "sponsors-add",
                component: SponsorsAdd
            },
            {
                path: "sponsors/edit",
                name: "sponsors-edit",
                component: SponsorsEdit
            },
            {
                path: "sponsors/booth",
                name: "sponsors-booth",
                component: SponsorsBoothChanges
            },
            {
                path: "sponsors/booth/preview/platinum",
                name: "sponsors-preview-platinum",
                component: SponsorsBoothPlatinum
            },
            {
                path: "sponsors/booth/preview/gold",
                name: "sponsors-preview-gold",
                component: SponsorsBoothGold
            },
            {
                path: "sponsors/booth/preview/silver",
                name: "sponsors-preview-silver",
                component: SponsorsBoothSilver
            },
            {
                path: "sponsors/booth/preview/bronze",
                name: "sponsors-preview-bronze",
                component: SponsorsBoothBronze
            },
            {
                path: "sponsors/booth/preview/supporter",
                name: "sponsors-preview-supporter",
                component: SponsorsBoothSupporter
            },
            {
                path: "election/active",
                name: "election-active",
                component: ElectionActive
            },
            {
                path: "election/history",
                name: "election-history",
                component: ElectionHistory
            },
            {
                path: "election/history/:id",
                name: "selected-election-history",
                component: ElectionHistory
            },
            {
                path: "election/add-nominees",
                name: "election-add-nominees",
                component: ElectionAddNominees
            },
            {
                path: "contest/poster-research",
                name: "PosterResearch",
                component: PosterResearch
            },
            {
                path: "contest/oral-research",
                name: "OralResearch",
                component: OralResearch
            },
            {
                path: "contest/photography",
                name: "Photography",
                component: Photography
            },
            {
                path: "collection-fees/list",
                name: "CollectionFeesList",
                component: CollectionFeesList
            },
            {
                path: "fiscal-period/active",
                name: "CollectionFeesList",
                component: FiscalPeriodActive
            },
            {
                path: "collection-fees/list-fiscal",
                name: "CollectionFeesListFiscal",
                component: CollectionFeesListFiscal
            },
            {
                path: "collection-fees/list-other",
                name: "CollectionFeesListOther",
                component: CollectionFeesListOther
            },
            {
                path: "payment-ledgers/list",
                name: "PaymentLedgersList",
                component: PaymentLedgersList
            },
            {
                path: "*",
                name: "404",
                component: NotFound
            }
        ]
    }
];

export default routes;
