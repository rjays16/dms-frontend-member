<template>
    <aside class="main-sidebar bg-white">
        <!-- sidebar: style can be found in sidebar.less -->
        <div>
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li v-if="roleCheck('member')" class="header">
                        MEMBERSHIP
                    </li>
                    <li v-if="roleCheck('member')">
                        <router-link to="/admin/announcements">
                            <i class="ti-signal"></i> <span>Announcements</span>
                        </router-link>
                    </li>
                    <li v-if="roleCheck('member')" class="treeview">
                        <a href="#">
                            <i class="ti-user"></i> <span>Members</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu pb-2">
                            <li v-if="roleCheck('member')" class="treeview">
                                <a href="#">
                                    <i class="ti-user mr-2"></i><span>For Registration</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu pb-3">
                                    <li>
                                        <router-link to="/admin/members/applicant">
                                            <div class="inline">
                                                <avatar inline
                                                    username="Applicant Members"
                                                    :size="20"
                                                    color="#fff"
                                                ></avatar>
                                                Applicant
                                            </div>
                                        </router-link>
                                    </li>
                                    <!-- <li>
                                        <router-link to="/admin/members/for-processing">
                                            <avatar inline
                                                username="For Processing Members"
                                                :size="20"
                                                color="#fff"
                                            ></avatar>
                                            For Processing
                                        </router-link>
                                    </li> -->
                                    <li>
                                        <router-link to="/admin/members/declined">
                                            <avatar inline
                                                username="Not Approved Members"
                                                :size="20"
                                                color="#fff"
                                            ></avatar>
                                            Not Approved
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                            <li v-if="roleCheck('member')" class="treeview">
                                <a href="#">
                                    <i class="ti-user mr-2"></i><span>Registered</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu pb-3">
                                    <li>
                                        <router-link to="/admin/members/active">
                                            <avatar inline
                                                username="Active Members"
                                                :size="20"
                                                color="#fff"
                                            ></avatar>
                                            Active
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link to="/admin/members/for-processing">
                                            <avatar inline
                                                username="For Processing Members"
                                                :size="20"
                                                color="#fff"
                                            ></avatar>
                                            For Processing
                                        </router-link>
                                    </li>
                                    <li>
                                        <router-link to="/admin/members/inactive">
                                            <avatar inline
                                                username="Inactive Members"
                                                :size="20"
                                                color="#fff"
                                            ></avatar>
                                            In-Active
                                        </router-link>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li v-if="roleCheck('member')" class="treeview">
                        <a href="#">
                            <i class="ti-clipboard"></i> <span>Election</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/admin/election/active">
                                    <avatar
                                        username="Active Period"
                                        :size="20"
                                        color="#fff"
                                    ></avatar>
                                    Active Period
                            </router-link>
                            </li>
                            <li>
                                <router-link
                                    to="/admin/election/history/passed"
                                >
                                    <avatar
                                        username="Election History"
                                        :size="20"
                                        color="#fff"
                                    ></avatar>
                                    History
                            </router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="roleCheck('member')" class="treeview">
                        <a href="#">
                            <i class="ti-layout-accordion-separated"></i>
                            <span>Site Settings</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/admin/site/header">
                                    <avatar
                                        username="Header"
                                        :size="20"
                                        color="#fff"
                                    ></avatar
                                    >Header</router-link
                                >
                            </li>
                            <li>
                                <router-link to="/admin/site/footer">
                                    <avatar
                                        username="Footer"
                                        :size="20"
                                        color="#fff"
                                    ></avatar
                                    >Footer</router-link
                                >
                            </li>
                        </ul>
                    </li>
                    <li v-if="roleCheck('member')" class="treeview">
                        <a href="#">
                            <i class="ti-home"></i> <span>Page Banners</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/admin/banner/home">
                                    <avatar
                                        username="Home Page"
                                        :size="20"
                                        color="#fff"
                                    ></avatar
                                    >Home Page
                                </router-link>
                            </li>
                            <li>
                                <router-link to="/admin/banner/login">
                                    <avatar
                                        username="Login Page"
                                        :size="20"
                                        color="#fff"
                                    ></avatar
                                    >Login Page
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="roleCheck('member')">
                        <router-link to="/admin/collection-fees/list">
                            <i class="ti-clipboard"></i>
                            <span>Collection Fees</span>
                        </router-link>
                    </li>
                      <li v-if="roleCheck('member')">
                        <router-link to="/admin/fiscal-period/active">
                            <i class="ti-calendar"></i>
                            <span>Fiscal Period</span>
                        </router-link>
                    </li>
                    <li v-if="roleCheck('member')">
                        <router-link to="/admin/payment-ledgers/list">
                            <i class="ti-notepad"></i>
                            <span>Payment Ledgers</span>
                        </router-link>
                    </li>

                    <li class="header">SESSIONS</li>
                    <li>
                        <router-link to="/admin/sessions">
                            <i class="dripicons dripicons-media-play"></i>
                            <span>Sessions</span>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/admin/rooms">
                            <i class="dripicons dripicons-view-thumb"></i>
                            <span>Rooms</span>
                        </router-link>
                    </li>
                    <li v-if="roleCheck('member')" class="treeview">
                        <a href="#">
                            <i class="dripicons dripicons-tags"></i>
                            <span>Tags</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/admin/tags">
                                    <avatar
                                        username="Tag List"
                                        :size="20"
                                        color="#fff"
                                    ></avatar
                                    >Tag List</router-link
                                >
                            </li>
                            <li>
                                <router-link to="/admin/tags/users">
                                    <avatar
                                        username="Assign Tags"
                                        :size="20"
                                        color="#fff"
                                    ></avatar
                                    >Assign Tags</router-link
                                >
                            </li>
                        </ul>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </div>
    </aside>
</template>
<script>
import Avatar from "vue-avatar/dist/Avatar.vue";
export default {
    name: "DashboardSidebar",
    data() {
        return {
            token: "",
            user_role: ""
        };
    },
    mounted: function() {
        $(document).ready(function($) {
            $(".main-sidebar > div").slimScroll({
                width: "230px",
                position: "left",
                size: "5px",
                height: "95vh"
            });
        });
        this.getTokenCookie();
        this.getUserRoleCookie();
    },
    methods: {
        roleCheck(type) {
            if (type === "member") {
                if (
                    this.user_role === "admin" ||
                    this.user_role === "super_admin"
                ) {
                    return true;
                } else if (this.user_role === "admin_pcr") {
                    return true;
                } else if (this.user_role === "admin_convention") {
                    return false;
                } else {
                    return false;
                }
            }
            if (type === "convention") {
                if (
                    this.user_role === "admin" ||
                    this.user_role === "super_admin"
                ) {
                    return true;
                } else if (this.user_role === "admin_pcr") {
                    return false;
                } else if (this.user_role === "admin_convention") {
                    return true;
                } else {
                    return false;
                }
            }
            if (type === "convention-super") {
                if (this.user_role === "super_admin") {
                    return true;
                } else {
                    return false;
                }
            }
        },
        getTokenCookie() {
            var token = "token";
            var match = document.cookie.match(
                new RegExp("(^| )" + token + "=([^;]+)")
            );
            if (match) {
                this.token = match[2];
            } else {
                // console.log("No token found");
            }
        },
        getUserRoleCookie() {
            var token = "user_role";
            var match = document.cookie.match(
                new RegExp("(^| )" + token + "=([^;]+)")
            );
            if (match) {
                this.user_role = match[2];
            } else {
                // console.log("No token found");
            }
        }
    },
    components: {
        Avatar
    }
};
</script>

<style scoped>
.sidebar-menu {
    padding-bottom: 50px;
}
</style>
