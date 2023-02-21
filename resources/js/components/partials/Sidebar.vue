<template>
    <aside class="main-sidebar bg-white">
        <!-- sidebar: style can be found in sidebar.less -->
        <div>
            <section class="sidebar">
                <!-- /.search form -->
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="image"> <img src="/images/img/avatar2.png" class="img-circle" alt="User Image"> </div>
                    <div class="info">
                        <p>Good Job, Matirasa</p>
                        <p><small>You haven,t miss any task this week!</small></p>
                        <div> <a href="#"><i class="ti-panel"></i><small>Settings</small></a> <a href="#"><i class="ti-user"></i><small>Profile</small></a> <a href="#"><i class="ti-power-off"></i><small>Logout</small></a> </div>
                    </div> <img class="bg-user" src="/images/img/avatar-bg.png" alt="User Image"> </div>
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu">
                    <li class="treeview">
                        <a href="#"> <i class="ti-desktop"></i> <span>Members</span> <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span> 
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <router-link to="/admin/members/applicant">
                                    <avatar username="Pending Members" :size='20' color="#fff"></avatar>Pending</router-link>
                            </li>
                            <li>
                                <router-link to="/admin/members/for-processing">
                                    <avatar username="Approved Members" :size='20' color="#fff"></avatar>Approved</router-link>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <router-link to="/"> <i class="ti-email"></i> <span>Dashboard</span> </router-link>
                    </li>
                    <li>
                        <router-link to="/admin/vouchercodes"> <i class="ti-comment-alt"></i> <span>Voucher Codes</span> </router-link>
                    </li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </div>
    </aside>
</template>
<script>
import Avatar from 'vue-avatar/dist/Avatar.vue'
export default {
  name: 'DashboardSidebar',
  mounted: function () {
    $(document).ready(function ($) {
      $('.main-sidebar > div').slimScroll({
        width: '230px',
        position: 'left',
        size: '5px',
        height: '95vh'
      })
    })
  },
  components: {
    Avatar
  }
}
</script>