<template>
	<transition name="el-zoom-in-top">
		<div class="content-wrapper page-site-profile">
			<section class="page-header">
				<div class="page-site-profile-header">
					<div class="page-site-profile-header-text">
                        {{ society_name }} Members
                    </div>
				</div>
			</section>
            <section class="content width-80">
                <div class="card">
                    <div class="table-responsive mt-3">
                        <el-table
                            v-loading="tableLoading"
                            ref="memberTable"
                            stripe
                            :data="members.data"
                            class="member-approved-table"
                            style="width: 100%">
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="last_name"
                                label="Last Name">
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="first_name"
                                label="First Name">
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="email"
                                label="Email">
                            </el-table-column>
                            <el-table-column
                                prop="actions"
                                label="View Details"
                                align="center"
                                class-name="cell-actions">
                                <template slot-scope="scope">
                                    <el-button plain class="my-1 mx-0" size="mini" @click="review(scope.row.id)" type="primary">
                                        View Details
                                    </el-button>
                                </template>
                            </el-table-column>    
                        </el-table>

                        <div class="block text-center my-3" v-if="members.data.length > 20">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedMembers(members.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in members.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedMembers(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedMembers(members.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>  
                    </div>
                </div>
                <el-dialog class="member-review-dialog" title="View Member Details" :visible.sync="dialogReviewTab">
                    <table width="100%" cellspacing="0" cellpadding="7">     
                        <tr>
                            <td width="30%"><span class="font-weight-bold">First Name: </span></td>
                            <td width="70%">{{ currentReview.first_name }}</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Middle Name: </span></td>
                            <td>{{ currentReview.middle_name }}</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Last Name: </span></td>
                            <td>{{ currentReview.last_name }}</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Date of Birth: </span></td>
                            <td>{{ new Date(currentReview.date_of_birth) | moment("MMMM D, YYYY") }}</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Email: </span></td>
                            <td>{{ currentReview.email }}</td>
                        </tr>
                        <tr>
                            <td><span class="font-weight-bold">Contact Number: </span></td>
                            <td>{{ currentReview.contact_number }}</td>
                        </tr>         
                        <tr>
                            <td><span class="font-weight-bold">Main Hospital Affiliation: </span></td>
                            <td>{{ currentReview.place_of_practice }}</td>
                        </tr>            
                        <tr>
                            <td  valign="top"><span class="font-weight-bold">Address: </span></td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="mb-1">City: {{ review_address_split[0] }}</div>
                                        <div>State/Region: {{ review_address_split[1] }}</div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div>Country: {{ review_address_split[2] }}</div>
                                        <div>Zip Code: {{ review_address_split[3] }}</div>                            
                                    </div>
                                </div>
                            </td>                
                        </tr>
                        <tr v-if="currentReview.is_trainee === 0">
                            <td><span class="font-weight-bold">Membership: </span></td>
                            <td>{{ currentReview.membership_type_name ? currentReview.membership_type_name : "None" }}</td>
                        </tr>
                    </table>
                </el-dialog>
            </section>
		</div>
	</transition>
</template>

<script>
export default {
    name: "AffiliateSociety",
    data() {
        return {
            post_id: this.$route.query.id,
            token: this.$cookies.get('token'),
            apiDomain: this.$store.state.apiUrl,
            
            tableLoading: false,
            members: {
                data: [],
            },
            society_name: null,

            currentReview: [],
            dialogReviewTab: false,
            review_address_split: [],
        };
    },
    methods: {
        getMembers() {
            axios.get(this.apiDomain + `/api/affiliate-society/${this.post_id}/members`, {
                headers: { Authorization: "Bearer " + this.token }
            })
			.then(res => {
                let data = res.data
                this.society_name = data.society_name
                this.members = data.members
			})
			.catch(err => {
                this.$message.error(err.response.data.message)
			})
        },
        getPagedMembers(url) {
            if(url) {
                this.tableLoading = true
                axios.get(url, {
                    headers: { "Authorization": "Bearer " + this.token }
                })
                .then(res => {
                    if(res.data) {
                        this.members = res.data;
                        this.tableLoading = false
                    }
                })
                .catch(err => {
                    console.error(err)
                    this.tableLoading = true
                });	
            }
        },
        review(id) {
            this.dialogReviewTab = true
            axios.get(this.apiDomain + '/api/pcr/member/' + id, {
                headers: { "Authorization": "Bearer " + this.token }
            }).then(response => {
				if(response.data) {
                    this.currentReview = response.data
                    var address = this.currentReview.address.slice(0, -1)
                    var address_array = address.split(',')
                    this.review_address_split = address_array
                }
			})
			.catch(err => {
                console.error(err)
			});	            
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
    },
    mounted() {
        this.getMembers()
    }
};
</script>

<style lang="scss" scoped>
.pcr-pagination,
.pcr-pagination li {
    display: inline;
}

.member-review-dialog {
    span, div {
        word-break: break-word;
    }
}
</style>