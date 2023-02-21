<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mt-2">
                            <i class="el-icon-s-home"></i>
                            <span> / Tags / Assign Tags</span>
                        </div>
                    </div>
                </div>                
            </div>              
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <h1>Assign Tags to Active Members</h1>
                    <div class="row mb-4">
                        <div class="col-sm-12 col-md-12 text-right">
                            <el-input
                                class="search-box"
                                placeholder="Search"
                                v-model="searchTerm"
                                @keyup.enter.native="getSearchResults">
                                <i slot="suffix" class="el-input__icon el-icon-search"></i>
                            </el-input>                            
                            <el-button class="ml-2" :loading="tableLoading" @click="getSearchResults">Search</el-button>
                        </div>
                        <div v-if="showSearchRes" class="col-sm-12 col-md-12 text-center">
                            <div class="show-search-res">
                                <span class="show-search-res-txt">Showing Search Results...</span>
                                <el-button class="ml-2" size="mini" type="info" :loading="tableLoading" @click="clearSearchResults">Clear</el-button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <el-table
                            v-loading="tableLoading"
                            stripe
                            :data="members.data"
                            class="member-approved-table"
                            style="width: 100%">
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="Name"
                                label="Name">
                                <template slot-scope="scope">
                                    <div class="table-user-name">{{ scope.row.last_name }}, {{ scope.row.first_name }}</div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="email"
                                label="Email">
                            </el-table-column>
                            <el-table-column
                                prop="tag_id"
                                label="Tag">
                                <template slot-scope="scope">
                                    <span v-if="scope.row.user">{{ getUserTag(scope.row.user.tag_id) }}</span>
                                    <span v-else>Unassigned</span>
                                </template>                                
                            </el-table-column>
                            
                            <el-table-column
                                prop="actions"
                                label="Actions"
                                align="center">
                                <template slot-scope="scope">
                                    <el-button size="mini" :disabled="!scope.row.user" @click="editUser(scope.row.user.id)" type="primary" icon="el-icon-edit">Update</el-button>
                                </template>
                            </el-table-column>                             
                        </el-table>

                        <div class="block text-center mt-5" v-if="!showSearchRes">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedApprovedMembers(members.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in members.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedApprovedMembers(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedApprovedMembers(members.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>                        


                    </div>
                           
                </div>

            </div>
        </div>



        
    </div>
</template>

<script>
const _spinner = '<i class="fa fa-spinner fa-spin"></i>';

import moment from 'moment'

import elTableExport from "el-table-export"

export default {
    data() {
        return {
            members: [],
            review_address_split: [],
            spinner: _spinner,
            is_approving: false,
            indexClicked: '',
            currentReview: [],
            dialogReviewTab: false,
            dialogImageTab: false,
            currentImageView: '',
            review_approving: false,
            approvalConfirmationTab: false,
            deleteConfirmationTab: false,
            forApproveID: '',
            forDeleteID: '',
            token: '',
            thisServerUrl: window.location.origin + '/',
            tableLoading: false,
            searchTerm: '',
            showSearchRes: false,
            supportType: ["csv", "txt", "json", "xls"],
            exportType: "csv",
            exportDialog: false,
            exportProgress: false,
            allMembers: [],
            apiDomain: this.$store.state.apiUrl,
            tags: []
        }
    },
    created() {
        
    },
    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD YYYY');
        }
    },    
    methods: {
        fullname(item) {
            return item.first_name + " " + item.last_name;
        },

        residentInTraining(val) {
            return val ? "YES" : "NO";
        },
        checkMemberships(mem) {
            var memberships = []
            if (mem) {
                if (mem.includes('1')) {
                    memberships.push('DBPR')
                }
                if (mem.includes('2')) {
                    memberships.push('FPCR')
                }
                if (mem.includes('3')) {
                    memberships.push('FUSP')
                }
                if (mem.includes('4')) {
                    memberships.push('FCT-MRISP')
                }
                if (mem.includes('5')) {
                    memberships.push('FPSVIR')
                }
                if (mem.includes('6')) {
                    memberships.push('FPROS')
                }
                return memberships.join(', ')
            } else {
                return ''
            }

        },
        openImage(filepath) {
            // this.currentImageView = '/images/layout/loading.gif'
            this.dialogImageTab = true
            var fileurl = filepath.replace("public", "storage")
            this.currentImageView = this.apiDomain + "/" + fileurl
            // this.currentImageView = 'https://beta2.73rdpcrannualconvention.com/' + fileurl
        },
        clearImage() {
            this.currentImageView = ''
        },
        getSearchResults() {
            this.tableLoading = true
            axios.get(this.apiDomain + `/api/pcr/search?keyword=${this.searchTerm}`, {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.members.data = res.data;
                    // // console.log(this.members.data)
                    this.tableLoading = false
                    this.showSearchRes = true
                }
			})
			.catch(err => {
                // // console.log(err)
                this.tableLoading = false
			});		
        },
        clearSearchResults () {
            this.searchTerm = ''
            this.showSearchRes = false
            this.getInitialApprovedMembers()
        },
        getInitialApprovedMembers() {
            this.tableLoading = true
            axios.get(this.apiDomain + `/api/admin/members/for-processing/get`, {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.members = res.data
                    // // console.log(this.members)
                    this.tableLoading = false
                }
			})
			.catch(err => {
                // // console.log(err)
                this.tableLoading = false
			});		
        },
        getAllApprovedMembers() {
            this.exportDialog = true
            this.exportProgress = true
            axios.get(this.apiDomain + `/api/getallmembers`)
			.then(res => {
				if(res.data) {
                    this.allMembers = res.data
                    this.exportProgress = false
                    //// console.log(res.data)
                }
			})
			.catch(err => {
                // // console.log(err)
			});		
        },        
        getPagedApprovedMembers(url) {
            if (!url) {

            } else {
                this.tableLoading = true
                axios.get(url)
                .then(res => {
                    if(res.data) {
                        this.members = res.data;
                        //// console.log(this.members)
                        this.tableLoading = false
                    }
                })
                .catch(err => {
                    // // console.log(err)
                    this.tableLoading = true
                });	
            }
        },

        paginLabel(label) {
            var currentLabel = String(label)
            if (currentLabel.includes('Previous')) {
                return '<'
            } else if (currentLabel.includes('Next')) {
                return '>'
            } else {
                return currentLabel
            }
        },
        getTokenCookie() {
            var token = 'token'
            var match = document.cookie.match(new RegExp('(^| )' + token + '=([^;]+)'))
            if (match) {
                this.token = match[2].replace('%7C', '|')
                //// console.log(this.token)
            }
            else{
                // console.log('No token found');
            }
        },


        nameFormatter(row, column) {
            return row.last_name + ', ' + row.first_name
        },



        getAllTags() {
            axios.get(this.apiDomain + `/api/all/tags`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.tags = res.data.data;
                    //// console.log(res.data)
                }
			})
			.catch(err => {
				// // console.log(err);
			})
        },
        getUserTag(id) {
            let tempTag = this.tags
            
            tempTag = tempTag.filter((item) => {
            return (item.id === id)
            })
            
            if (tempTag.length === 0) {
                return 'None Selected'
            } else {
                //// console.log(tempTag[0].tag_name)
                return tempTag[0].tag_name
            }  
        },
        editUser(id) {
            this.$router.push('/admin/users/update?id=' + id)
        }         
    },
    mounted: function () {
        this.getTokenCookie()
        this.getInitialApprovedMembers()
        this.getAllTags()
        //this.getCurrentURL()
    } 
}
</script>

<style>

.content-wrapper {
    padding-top: 65px;
}

.add-content-box {
     height: 70px;
     width: 100%;
     border: 3px dashed #ccc;
}

.rounded-add-btn {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    text-align: center;
    height: 50px;
    width: 50px;
    border-radius: 50%;
    border: 3px dashed #ccc;
}

.add-btn-default {
    color: #ccc;
    transition: all 0.20s;
}

.add-btn-hovered {
    background-color: #ccc;
    color: #fff;
    transition: all 0.20s;
}

.fa-icon-custom {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.5em;
}
.paper-container {
	box-shadow: 0 10px 10px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
	padding: 20px 25px 35px 25px;
	background-color: #fff;
	margin-top: 30px;
}
.admin-conent h1 {
    color:#174A84;
    font-size:1rem;
    font-weight:bold;
    margin-bottom:20px;
}
.member-approved-table th {
    color:#131313;
}
.member-approved-table th .cell {
    word-break: normal;
}

.member-approved-table .el-table__row .cell {
    word-break: normal!important;
}
.table-user-name {
    text-transform:uppercase;
}
.export-dialog .el-dialog__body {
    word-break: normal!important;
    text-align: center;
}
</style>
