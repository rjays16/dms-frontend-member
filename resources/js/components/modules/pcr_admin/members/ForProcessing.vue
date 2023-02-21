<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mt-2">
                            <i class="el-icon-s-home"></i>
                            <span> / Members / For-Processing</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 text-right d-none">
                        <el-button size="small" type="success" @click="getForProcessingMembers()">Export</el-button>
                    </div>
                </div>                
            </div>              
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <h1>For Processing Members <span class="member-count" v-if="totalMembers">{{ totalMembers }}</span></h1>
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
                            class="member-table"
                            style="width: 100%">
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="Name"
                                width="200"
                                label="Name">
                                <template slot-scope="scope">
                                    <div class="table-user-name">{{ scope.row.last_name }}, {{ scope.row.first_name }}</div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                width="200"
                                prop="email"
                                label="Email">
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                width="120"
                                prop="prc_number"
                                label="PRC Number">
                            </el-table-column>
                            <el-table-column
                                prop="pma_number"
                                align="center"
                                width="150"
                                label="PMA Number">
                            </el-table-column>
                            <el-table-column
                                prop="memberships"
                                label="Memberships">
                                <template slot-scope="scope">
                                    {{ scope.row.membership_type_name ? scope.row.membership_type_name : "None" }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                prop="updated_at"
                                align="center"
                                label="Approval Date">
                                <template slot-scope="scope">
                                    <div>{{ scope.row.updated_at | moment }}</div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                prop="actions"
                                label="View Details"
                                align="center"
                                class-name="cell-actions">
                                <template slot-scope="scope">
                                    <el-button plain class="my-1 mx-0" size="mini" @click="review(scope.row.id)" type="primary" icon="el-icon-search">Details</el-button>
                                    <el-button plain class="my-1 mx-0" size="mini" :loading="is_resetting_password" :disabled="is_resetting_password"
                                        @click="resetPassword(scope.row.id)" type="warning" icon="el-icon-key">
                                        Reset Pass
                                    </el-button>
                                    <el-button plain class="my-1 mx-0" size="mini" @click="viewPaymentHistory(scope.row)" type="info" icon="el-icon-s-order">
                                        Payments
                                    </el-button>
                                    <el-button plain class="my-1 mx-0" size="mini" @click="deleteConfirmation(scope.row.id)" type="danger" icon="el-icon-delete">
                                        Delete
                                    </el-button>
                                </template>
                            </el-table-column>                             
                        </el-table>

                        <div class="block text-center mt-5" v-if="!showSearchRes && members.total !=0">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedForProcessingMembers(members.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in members.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedForProcessingMembers(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedForProcessingMembers(members.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>                        
                    </div>
                    <div class="table-responsive d-none export-table mt-5">
                        <el-table
                            v-loading="tableLoading"
                            ref="memberTable"
                            stripe
                            :data="allMembers"
                            class="member-table"
                            style="width: 100%">
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="last_name"
                                width="200"
                                label="Last Name">
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                prop="first_name"
                                width="200"
                                label="First Name">
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                width="200"
                                prop="email"
                                label="Email">
                            </el-table-column>
                            <el-table-column
                                class-name="cell-nowrap"
                                width="150"
                                prop="prc_number"
                                label="PRC Number">
                            </el-table-column>
                            <el-table-column
                                prop="membership_type_name"
                                label="Memberships">
                            </el-table-column>
                            <el-table-column
                                prop="updated_at"
                                align="center"
                                label="Approval Date">
                                <template slot-scope="scope">
                                    <div>{{ scope.row.updated_at | moment }}</div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                prop="prc_expiration"
                                align="center"
                                label="PRC Expiration Date">
                            </el-table-column>
                        </el-table>
                    </div>                    
                </div>
            </div>
        </div>

        <el-dialog :visible.sync="exportDialog" class="export-dialog">
            <div v-if="exportProgress">
                Please wait while for processing members data is being loaded. Depending on the number of members, this may take a few minutes.
            </div>
            <div v-else>
                Member data is now ready to be exported. Please click on 'Start Export' to export the data to CSV.
            </div>
            <div v-loading="exportProgress" class="mt-4">
                <el-button type="success" @click="exportFile()">Start Export</el-button>
                <el-button type="primary" @click="exportDialog = false">Cancel</el-button>
            </div>
        </el-dialog>
        <el-dialog class="member-view-image" :visible.sync="dialogImageTab" @close="clearImage()">
            <img :src="currentImageView" />
        </el-dialog>
        <el-dialog class="member-review-dialog text-center" title="View Member Details" :visible.sync="dialogReviewTab">
            <table width="100%" cellspacing="0" cellpadding="7">
                <tr class="text-center">
                    <td width="50%"><span class="font-weight-bold">First Name: </span></td>
                    <td width="50%">{{ currentReview.first_name }}</td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">Middle Name: </span></td>
                    <td>{{ currentReview.middle_name }}</td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">Last Name: </span></td>
                    <td>{{ currentReview.last_name }}</td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">Date of Birth: </span></td>
                    <td>{{ currentReview.date_of_birth | moment }}</td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">Email: </span></td>
                    <td>{{ currentReview.email }}</td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">Contact Number: </span></td>
                    <td>{{ currentReview.contact_number }}</td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">PMA Number: </span></td>
                    <td>{{ currentReview.pma_number }}</td>
                </tr>            
                <tr class="text-center" v-if="currentReview.prc_number != 0">
                    <td><span class="font-weight-bold">PRC Number: </span></td>
                    <td>{{ currentReview.prc_number }}</td>
                </tr>
                <tr class="text-center" v-if="currentReview.prc_upload">
                    <td><span class="font-weight-bold">PRC ID: </span></td>
                    <td><el-button @click="openImage(currentReview.prc_upload)" type="primary" icon="el-icon-view"></el-button></td>
                </tr>
                <tr class="text-center">
                    <td><span class="font-weight-bold">PRC Expiration: </span></td>
                    <td>{{ currentReview.prc_expiration }}</td>
                </tr>            
                <tr class="text-center">
                    <td><span class="font-weight-bold">Place of Practice: </span></td>
                    <td>{{ currentReview.place_of_practice }}</td>
                </tr> 
                <tr class="text-center" v-if="currentReview.is_trainee === 0">
                    <td><span class="font-weight-bold">Membership: </span></td>
                    <td>{{ currentReview.membership_type_name ? currentReview.membership_type_name : "None" }}</td>
                </tr>           
                <tr class="text-center">
                    <td  valign="top"><span class="font-weight-bold">Address: </span></td>
                    <td>
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div class="mb-1">City: {{ review_address_split[0] }}</div>
                                <div>State/Region: {{ review_address_split[1] }}</div>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12">
                                <div>Country: {{ review_address_split[2] }}</div>
                                <div>Zip Code: {{ review_address_split[3] }}</div>                            
                            </div>
                        </div>
                    </td>                
                </tr>
            </table>
        </el-dialog>
        <el-dialog class="member-payment-history custom" width="75%" title="View Payments" :visible.sync="dialogPaymentHistory">
            <div class="text-right d-none">
                <el-button size="small" type="success" @click="showCollectionFeesDialog = true">
                    Add New
                </el-button>
            </div>    
            <p><u>{{ paymentHistory.name }}</u></p>
            <el-table
                ref="memberTable"
                stripe
                :data="paymentHistory.data"
                class="member-table"
                style="width: 100%">
                <el-table-column
                    prop="order_id"
                    label="Order"> 
                    <template slot-scope="scope">
                        <span class="mob-col-desc font-weight-bold">Order: </span>
                        <div class="table-user-name">{{ scope.row.order.order_type_name }}</div>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="amount"
                    label="Order Amount">
                    <template slot-scope="scope">
                        <span class="mob-col-desc font-weight-bold">Order Amount: </span>
                        <div class="table-user-name">{{ scope.row.order.collection_fee.amount }}</div>
                    </template>                    
                </el-table-column>
                <el-table-column
                    prop="amount"
                    label="Paid Amount">
                    <template slot-scope="scope">
                        <span class="mob-col-desc font-weight-bold">Paid Amount: </span>
                        <div class="table-user-name">{{ scope.row.amount }}</div>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="amount"
                    label="Payment Method">
                    <template slot-scope="scope">
                        <span class="mob-col-desc font-weight-bold">Payment Method: </span>
                        <div class="table-user-name">{{ scope.row.method.name }}</div>
                    </template>                    
                </el-table-column>
                <el-table-column
                    label="Status"> 
                    <template slot-scope="scope">
                        <span class="mob-col-desc font-weight-bold">Status: </span>
                        <div class="table-user-name">{{ scope.row.order.order_status_name }}</div>
                    </template>
                </el-table-column> 
                <el-table-column
                    prop="date_paid"
                    label="Date Paid">
                    <template slot-scope="scope">
                        <span class="mob-col-desc font-weight-bold">Date Paid: </span>
                        <div>{{ scope.row.date_paid | moment }}</div>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="date_paid"
                    align="center"
                    label="Actions">
                    <template slot-scope="scope">
                        <el-button size="small" class="w-100 m-0 mb-2" @click="getDepositSlip(scope.row)" type="primary">View Deposit Slip</el-button>
                        <el-button v-if="scope.row.order.status != 2" size="small" class="w-100 m-0 mb-2" @click="showMarkPaid(scope.row)" type="primary">Mark as Paid</el-button>
                        <el-button size="small" class="w-100 m-0"  @click="deleteMemberPay(scope.row.id)" type="danger">Delete</el-button>
                    </template>
                </el-table-column>
            </el-table>

        </el-dialog>

            <el-dialog
                width="70%"
                class="custom"
                title="Deposit Slip"
                :visible.sync="dialogDepositSlip"
                @close="clearDepImage()">
                    <div>
                        <img :src="depImage" style="max-width:100%" />
                        <p v-if="noDeposit">No Deposit Slip Uploaded</p>
                    </div>
            </el-dialog>
            <el-dialog
                width="25%"
                class="dialog-custom"
                title="Mark as Paid"
                :visible.sync="dialogMarkPaid">
                <div>
                    <p class="font-weight-bold">Enter Amount Paid:</p>
                    <el-input-number v-model="newBalance" :min="0"></el-input-number>
                    
                </div>
                <el-button size="small" class="button-size mt-3 m-0"  @click="saveNewBalance()" type="success">Save</el-button>
            </el-dialog>

            <el-dialog class="collection-fees-dialog" title="Collection Fee" :visible.sync="showCollectionFeesDialog" :close-on-click-modal="false">
                <div class="form-group">
                    <label>Payment Type</label>
                    <el-select v-model="$v.formCollection.payment_type.$model" class="w-100" placeholder="Select">
                        <el-option
                            v-for="option in paymentTypes"
                            :key="option.id"
                            :label="option.name"
                            :value="option.id" />
                    </el-select>
                    <div v-if="$v.formCollection.payment_type.$dirty">
                        <div class="note color-FF2C2C" v-if="!$v.formCollection.payment_type.required">Required Field</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Year</label>
                    <el-date-picker class="w-100" v-model="$v.formCollection.year.$model" type="year" value-format="yyyy" placeholder="Select" />
                    <div v-if="$v.formCollection.year.$dirty">
                        <div class="note color-FF2C2C" v-if="!$v.formCollection.year.required">Required Field</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <el-input v-model="$v.formCollection.description.$model" type="textarea" :rows="4" />
                    <div v-if="$v.formCollection.description.$dirty">
                        <div class="note color-FF2C2C" v-if="!$v.formCollection.description.required">Required Field</div>
                        <div class="note color-FF2C2C" v-if="!$v.formCollection.description.maxLength">Max of {{ $v.formCollection.description.$params.maxLength.max }} characters.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <div class="text-left">
                        <el-input-number v-model="$v.formCollection.amount.$model" :min="0" :precision="0" :step="1" controls-position="right" class="w-100" />
                    </div>
                    <div v-if="$v.formCollection.amount.$dirty">
                        <div class="note color-FF2C2C" v-if="!$v.formCollection.amount.required">Required Field</div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <el-select v-model="$v.formCollection.status.$model" class="w-100" placeholder="Select">
                        <el-option
                            v-for="option in statusTypes"
                            :key="option.id"
                            :label="option.name"
                            :value="option.value" />
                    </el-select>
                    <div v-if="$v.formCollection.status.$dirty">
                        <div class="note color-FF2C2C" v-if="!$v.formCollection.status.required">Required Field</div>
                    </div>
                </div>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="showCollectionFeesDialog = false">Cancel</el-button>
                    <el-button type="primary" @click="recheckFormCollectionInputs" :disabled="disableSaveCFBtn">Save</el-button>
                </span>
            </el-dialog>         

        <el-dialog :visible.sync="dialogDeleteMember">
            <div class="text-center">Are you sure you want to delete the pending registration for this user?</div>
            <div class="text-center mt-3">
                <el-button :loading="is_approving" :disabled="is_approving" @click="deleteReg()" type="danger" icon="el-icon-delete">Delete</el-button>
                <el-button @click="cancelDelete" type="info">Cancel</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
const _spinner = '<i class="fa fa-spinner fa-spin"></i>';

import moment from 'moment'

import elTableExport from "el-table-export"

import { required, maxLength } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            members: [],
            review_address_split: [],
            spinner: _spinner,
            is_approving: false,
            is_resetting_password: false,
            indexClicked: '',
            currentReview: [],
            dialogReviewTab: false,
            dialogImageTab: false,
            dialogPaymentHistory: false,
            currentImageView: '',
            review_approving: false,
            approvalConfirmationTab: false,
            deleteConfirmationTab: false,
            forProcessingID: '',
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
            paymentHistory:{
                name:""
            },
            dialogDepositSlip: false,
            dialogMarkPaid: false,
            newBalance: 0,
            depImage: "",
            newBalOrderID: "",
            newBalMemID: "",
            noDeposit: true,
            dialogDeleteMember: false,
            forDeleteID: '',
            totalMembers: null,

            disableSaveCFBtn: false,
            disableSaveMemberCFBtn: false,

            memberPayData: {},

            showCollectionFeesDialog: false,
            showMemberCFDialog: false,
            forDeleteID: '',
            is_deleting: false,

            paymentTypes: [],
            statusTypes: [
                { id: 1, name: "Active", value: 1 },
                { id: 2, name: "Inactive", value: 0 },
            ],
            activeMembers: [],
            activeCollectionFees: [],
            deleteConfirmationTab: false,

            formCollection: {
                description: null,
                payment_type: 1,
                year: "",
                amount: null,
                status: 1,
            },

            formMemberCF: {
                member_id: null,
                collection_fee_id: null,
            },

            collectionFees: {
                data: [
                    // { id: 1, description: "Membership", payment_type: 1, payment_type_name: "Membership", amount: 1500, year: 2021, status: 1 } 
                ]
            }

        }
    },
    validations: {
        formCollection: {
            member_id: '',
            payment_type: { 
                required
            },
            year: {
                required
            },
            description: {
                required,
                maxLength: maxLength(255)
            },
            amount: {
                required
            },
            status: {
                required
            }
        },
        formMemberCF: {
            member_id: { 
                required
            },
            collection_fee_id: { 
                required
            }, 
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
            this.showSearchRes = true
            this.tableLoading = true
            axios.get(this.apiDomain + '/api/admin/members/for-processing', {
				params: {
                    is_search: true,
					keyword: this.searchTerm,
                },
                headers: { "Authorization": "Bearer " + this.token }
			})
			.then(res => {
                this.members = res.data.data
                this.totalMembers = res.data.data.total
                this.tableLoading = false
			})
			.catch(err => {
                console.log(err)
                this.tableLoading = false
			});
        },
        clearSearchResults () {
            this.searchTerm = ''
            this.showSearchRes = false
            this.getForProcessingMembers()
        },
        getForProcessingMembers() {
            this.tableLoading = true
            axios.get(this.apiDomain + '/api/admin/members/for-processing', {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
                this.members = res.data.data
                this.totalMembers = res.data.data.total
                this.tableLoading = false
			})
			.catch(err => {
                console.log(err)
                this.tableLoading = false
			});		
        },
        getPagedForProcessingMembers(url) {
            if (!url) {
                this.members = []
            } else {
                this.tableLoading = true
                axios.get(url, {
                    headers: { "Authorization": "Bearer " + this.token }
                })
                .then(res => {
                    this.members = res.data.data
                    this.tableLoading = false
                })
                .catch(err => {
                    // console.log(err)
                    this.tableLoading = true
                });	
            }
        },

        review(id) {
            axios.get(this.apiDomain + `/api/pcr/member/${id}`, {
                headers: { "Authorization": "Bearer " + this.token }
            }).then(response => {
				if(response.data) {
                    this.currentReview = response.data
                    this.is_approving = false
                    var address = this.currentReview.address.slice(0, -1)
                    var address_array = address.split(',')
                    this.review_address_split = address_array
                    this.is_approving = true
                    this.dialogReviewTab = true
                }
			})
			.catch(error => {
                // console.log(error);
                this.is_approving = false
			});	             
        },
        viewPaymentHistory(row) {
            // console.log(row)
            this.formCollection.member_id = row.id
            this.memberPayData = row
            // console.log(this.memberPayData)
            const name = this.nameFormatter(row)
            this.dialogPaymentHistory = true 

            axios.get(this.apiDomain + `/api/payments/${row.id}`, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                this.tableLoading = false
                // console.log(res)
                this.paymentHistory.name = name
                this.paymentHistory.data = res.data
			})
			.catch(err => {
                // console.log(err)
                this.tableLoading = false
			});	 
        },
        refreshPaymentHistory(id) {
            this.dialogPaymentHistory = false
            axios.get(this.apiDomain + `/api/payments/${id}`, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                this.paymentHistory.data = res.data
                this.dialogPaymentHistory = true
			})
			.catch(err => {
                // console.log(err)
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
        getCurrentURL() {
            var currentUrl = window.location.href
            // // console.log('go: ' + currentUrl)
            if (currentUrl.includes('127.0.0.1')) {
                this.thisServerUrl = ''
            } else {
                this.thisServerUrl = process.env.MIX_CONVENTION_LIVE_URL
            }
            //// console.log(this.thisServerUrl)
            // this.thisServerUrl = 'https://73rdpcrannualconvention.com/'
        },
        exportFile() {
            elTableExport(this.$refs.memberTable, {
                fileName: "for-processing-members",
                type: this.exportType,
                useFormatter: true,
            })
                .then(() => {
                    console.info("ok");
                    this.exportDialog = false
                })
                .catch((err) => {
                    console.info(err);
                    this.exportDialog = false
                });
        },
        nameFormatter(row, column) {
            return row.last_name + ', ' + row.first_name
        },
        residentFormatter(row, column) {
            if (row.is_trainee === 0) {
                return 'No'
            } else {
                return 'Yes'
            }
        },
        membershipFormatter(row, column) {
            var memberships = []
            if (row.memberships) {
                if (row.memberships.includes('1')) {
                    memberships.push('DBPR')
                }
                if (row.memberships.includes('2')) {
                    memberships.push('FPCR')
                }
                if (row.memberships.includes('3')) {
                    memberships.push('FUSP')
                }
                if (row.memberships.includes('4')) {
                    memberships.push('FCT-MRISP')
                }
                if (row.memberships.includes('5')) {
                    memberships.push('FPSVIR')
                }
                if (row.memberships.includes('6')) {
                    memberships.push('FPROS')
                }
                return memberships.join(', ')
            } else {
                return 'None Selected'
            }
        },
        chapterFormatter(row, column) {
            if (row.chapter_id === 1) {
                return 'Northern Luzon'
            } else if (row.chapter_id === 2) {
                return 'Central Luzon'
            } else if (row.chapter_id === 3) {
                return 'Southern Luzon'
            } else if (row.chapter_id === 4) {
                return 'NCR'
            } else if (row.chapter_id === 5) {
                return 'Cebu'
            } else if (row.chapter_id === 6) {
                return 'Negros'
            } else if (row.chapter_id === 7) {
                return 'Panay Island'
            } else if (row.chapter_id === 8) {
                return 'Southern Mindanao'
            } else if (row.chapter_id === 9) {
                return 'Northern Mindanao'
            }
        },
        goToImport() {
            window.open(this.apiDomain + '/admin/import', '_blank')
        },
        getDepositSlip(row) {
            axios.get(this.apiDomain + `/api/payments/deposit-slip/${row.id}`, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                //// console.log(res)
                this.noDeposit = false
                this.dialogDepositSlip = true
                var fileurl = res.data.replace("public", "storage")
                this.depImage = this.apiDomain + "/" + fileurl
                // console.log(this.depImage)              
			})
			.catch(err => {
                // console.log(err)
                this.dialogDepositSlip = true
                this.noDeposit = true
			});	 
        },
        showMarkPaid(row) {
            this.dialogMarkPaid = true
            this.newBalOrderID = row.order_id
            this.newBalMemID = row.pcr_member_id
        },
        saveNewBalance() {
            axios.post(this.apiDomain + `/api/orders/user/update`, {
                order_id: this.newBalOrderID,
                pcr_member_id: this.newBalMemID,
                balance: this.newBalance},
                {
                headers: { Authorization: "Bearer " + this.$cookies.get("token"), }
            })
            .then(response => {
                // console.log(response)
                this.$message.success('Payment Marked as Paid.')
                this.newBalOrderID = ""
                this.newBalMemID = ""
                this.newBalance = 0
                this.dialogMarkPaid = false
                this.dialogPaymentHistory = false
            })
            .catch(error => {
                this.dialogMarkPaid = false
                this.dialogPaymentHistory = false
                this.$message.error('Error in updating payment.')
                // console.log(error)
            })            
        },
        clearDepImage() {
            this.depImage = ""
            this.noDeposit = true
        },
        deleteConfirmation(id) {
            this.dialogDeleteMember = true
            this.forDeleteID = id
        },
        cancelDelete() {
            this.dialogDeleteMember = false
            this.forDeleteID = ''
        },               
        deleteReg() {
            var id = this.forDeleteID
            this.is_approving = true
            axios.delete(this.apiDomain + '/api/pcr/member/' + id, {
                headers: { "Authorization": "Bearer " + this.token }
            }).then(res => {
                if(res.data) {
                    this.getForProcessingMembers();
                    this.is_approving = false
                    this.dialogDeleteMember = false
                    this.dialogReviewTab = false
                    this.$message.success('Selected member has been deleted.')
                                
                }
            })
            .catch(err => {
                // console.log(err);
                this.$message.error('Error deleting selected member.')
                this.is_approving = false
                this.dialogDeleteMember = false
                this.dialogReviewTab = false
            })
        },
        getTotalMembers() {
            axios.get(this.apiDomain + `/api/pcr/member/total/active`, {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    // console.log(res.data)
                    this.totalMembers = res.data
                }
			})
			.catch(err => {
                console.log(err)
                this.totalMembers = null
			});            
        },
        recheckFormCollectionInputs() {
            this.$v.formCollection.$touch()
            if(this.$v.formCollection.$invalid) {
                this.$message.error("Please fill in the required fields properly.")
            } else {
                this.disableSaveCFBtn = true
                this.saveCollectionFee()
            }
        },
        saveCollectionFee() {
            // // console.log(this.formCollection)

            this.$confirm('This Collection Fee will be added for this member. Continue?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			}).then(() => {
				axios.post(this.apiDomain + "/api/collection-fees/member/create", this.formCollection, {
                    headers: {
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then(res => {
                    this.$message.success(res.data.message)
                    this.disableSaveCFBtn = false
                    this.showCollectionFeesDialog = false
                    this.dialogPaymentHistory = false
                })
                .catch(err => {
                    this.$message.error("An error occurred. Please contact the site admin.")
                    // console.log(err)
                    this.disableSaveCFBtn = false
                    this.showCollectionFeesDialog = false
                    this.dialogPaymentHistory = false
                });
			}).catch(() => {
				this.$message({
					type: 'info',
					message: 'Cancelled'
				});
                this.disableSaveCFBtn = false
			});
        },
        deleteMemberPay(id) {
            // // console.log(this.formCollection)

            this.$confirm('This payment will be deleted for this member. Continue?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			}).then(() => {
                axios.delete(this.apiDomain + '/api/payments/' + id, {
                    headers: { "Authorization": "Bearer " + this.token }
                }).then(res => {
                    if(res.data) {
                        this.$message.success(res.data.message)
                        this.showCollectionFeesDialog = false
                        this.refreshPaymentHistory(this.memberPayData.id)
                        this.disableSaveCFBtn = false
                        this.$message.success('The selected payment has been deleted.')
                    }
                })
                .catch(err => {
                    this.$message.error("Error deleting selected payment.")
                    // console.log(err)
                    this.disableSaveCFBtn = false
                    this.showCollectionFeesDialog = false
                    this.refreshPaymentHistory(this.memberPayData.id)
                });
			}).catch(() => {
				this.$message({
					type: 'info',
					message: 'Cancelled'
				});
                this.disableSaveCFBtn = false
			});
        },        
        getPaymentTypes() {
            axios.get(this.apiDomain + '/api/orders/types', {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                this.paymentTypes = res.data
			})
			.catch(err => {
                this.dialogDepositSlip = false
			});	
        },
        resetPassword(member_id) {
            this.$confirm("This will reset the member's password. Continue?", 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			}).then(() => {
                this.is_resetting_password = true

                axios.post(this.apiDomain + `/api/pcr/member/${member_id}/reset-password`, null,
                    { headers: { Authorization: "Bearer " + this.$cookies.get("token") }
                })
                .then(res => {
                    this.$message.success(res.data.message)
                    this.is_resetting_password = false
                })
                .catch(err => {
                    this.$message.error(err.response.data.message)
                    this.is_resetting_password = false
                });
			}).catch(() => {
				this.$message({
					type: 'info',
					message: 'Cancelled'
				});
			});
        },
        bindPusher() {
            console.log("Binding Pusher...");
            var self = this;
            var channel = pusher.subscribe('mdmsi');
            channel.bind("order-fee", function() {
                self.getDeclinedMembers()
            });
        }
    },
    mounted: function () {
        this.getTokenCookie()
        this.getForProcessingMembers()
        this.getPaymentTypes()
        this.bindPusher()
        //this.getCurrentURL()
    }
}
</script>


<style lang="scss" scoped>
.el-input-number {
    ::v-deep input {
        text-align: left;
    }
}
.dialog-custom{
    padding-top: 10vh;
    align-items: center;
    text-align: center;
}
.button-size {
    width: 180px;
}
.custom {
    margin-left: 10vh;
}
</style>

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
.member-table th {
    color:#131313;
}
.member-table th .cell {
    word-break: normal;
}

.member-table .el-table__row .cell {
    word-break: normal!important;
}
.table-user-name {
    text-transform:uppercase;
}
.export-dialog .el-dialog__body {
    word-break: normal!important;
    text-align: center;
}
.member-count {
    display: inline-block;
    background: #174A84;
    color: #ffffff;
    padding:3px 8px;
    border-radius: 4px;
    margin-left: 3px;
}


    .member-payment-history .mob-col-desc {
        display: none;
    }

	@media only screen and (max-width: 767px) {
        .member-payment-history .el-table__header {
            display: none;
        }
        .member-payment-history table,
        .member-payment-history table tr,
        .member-payment-history table td,
        .member-payment-history table tbody,
        .member-payment-history table colgroup,
        .member-payment-history table colgroup col {
            width: 100%!important;
            display: block;
            border:none;
        }
        .member-payment-history table {
            border-bottom: 3px solid rgba(0,0,0,0.2);
        }
        .member-payment-history table tr {
            border-top: 2px solid rgba(0,0,0,0.2);
            border-left: 2px solid rgba(0,0,0,0.2);
            border-right: 2px solid rgba(0,0,0,0.2);

        }
        .member-payment-history table {
            table-layout: auto;
        }
        .member-payment-history .ipay-btn {
            text-align: center;
        }
        .member-payment-history .ipay-btn button {
            width: 50%;
        }
        .member-payment-history .mob-col-desc {
            display: inline;
        }        
	}

</style>