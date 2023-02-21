<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="mt-2">
                            <i class="el-icon-s-home"></i>
                            <span> / Collection Fees / Fiscal Fee List</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9 text-right">
                        <el-button size="small" class="mb-2" type="success" @click="loadAddCFToAllDialogData" :loading="showAddCFToAllDialog">
                            Apply Collection to All Active Members
                        </el-button>
                        <el-button size="small" class="mb-2" type="success" @click="loadMemberCFDialogData" :loading="showCollectionFeesDialog">
                            Apply Collection to a Specific Active Member
                        </el-button>
                        <el-button size="small" class="mb-2" type="success" @click="showCollectionFeesDialog = true">
                            Add New
                        </el-button>
                    </div>
                </div>                
            </div>              
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <h1>Collection Fees</h1>
                    <div class="table-responsive">
                        <el-table
                            v-loading="tableLoading"
                            stripe
                            :data="collectionFees.data"
                            class="member-approved-table"
                            style="width: 100%">
                            <el-table-column
                                prop="payment_type_name"
                                label="Payment Type">
                            </el-table-column>
                            <el-table-column
                                prop="description"
                                label="Description">
                            </el-table-column>
                            <el-table-column
                                prop="amount"
                                label="Amount">
                            </el-table-column>
                            <el-table-column
                                prop="status"
                                label="Status">
                                <template slot-scope="scope">
                                    <div class="text-uppercase">{{ scope.row.status === 0 ? 'Inactive' : 'Active' }}</div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                prop="start_period"
                                label="Start Date">
                                 <template slot-scope="props">
                                    {{ props.row.start_period | moment }}
                                </template>
                            </el-table-column>
                              <el-table-column
                                prop="end_period"
                                label="End Date">
                                <template slot-scope="props">
                                    {{ props.row.end_period | moment }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                prop="actions"
                                label="Actions">
                                <template slot-scope="scope">
                                    <div>
                                        <el-button class="w-100 mb-1" plain type="success" size="mini" @click="editItem(scope.row)">
                                            Edit
                                        </el-button>
                                        <el-button class="w-100 ml-0" plain type="danger" size="mini" @click="deleteConfirmation(scope.row.id)">
                                            Delete
                                        </el-button>
                                    </div>
                                </template>
                            </el-table-column>                 
                        </el-table>

                        <div class="block text-center mt-5">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedCollectionFees(collectionFees.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in collectionFees.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedCollectionFees(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedCollectionFees(collectionFees.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>
                    </div>                 
                </div>
            </div>
        </div>

        <el-dialog class="collection-fees-dialog" title="Collection Fee" :visible.sync="showCollectionFeesDialog" :close-on-click-modal="false">
            <div class="form-group">
                <label>Payment Type</label>
                <el-select v-model="$v.formCollection.payment_type.$model" class="w-100" @change="resetInput" placeholder="Select">
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

            <div v-if="$v.formCollection.payment_type.$model == 1 || $v.formCollection.payment_type.$model == 2">
            <div class="form-group">
                <label>Start Period</label>
                <el-date-picker class="w-100" v-model="$v.formCollection.start_period.$model" type="month" format="MMMM, yyyy" value-format="yyyy-MM-dd HH:mm:ss" picker-options="pickerOptions" placeholder="Start Date" />
                <div v-if="$v.formCollection.start_period.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.formCollection.start_period.required">*Required Field</div>
                </div>
            </div>

            <div class="pb-2" v-if="errors.end_period">
            <div class="error-message">
            <span class="error-text">* {{ errors.end_period[0] }}</span>
            </div>
            </div>

            <div class="form-group">
                <label>End Period</label>
                <el-date-picker class="w-100" v-model="$v.formCollection.end_period.$model" type="month" format="MMMM, yyyy" value-format="yyyy-MM-dd HH:mm:ss" picker-options="pickerOptions" placeholder="End Date" />
                <div v-if="$v.formCollection.end_period.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.formCollection.end_period.required">*Required Field</div>
                </div>
            </div>
            </div>

            <div v-else>
            <div class="form-group">
                <label>Year</label>
                <el-date-picker class="w-100" v-model="$v.formCollection.year.$model" type="year" value-format="yyyy" placeholder="Select" />
                <div v-if="$v.formCollection.year.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.formCollection.year.required">*Required Field</div>
                </div>
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
                <el-input-number v-model="$v.formCollection.amount.$model" :min="0" :precision="0" :step="1" controls-position="right" class="w-100" />
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
            <!-- <div class="form-group">
                <label>Valid Until:</label>
                <el-date-picker class="w-100" v-model="$v.formCollection.valid_until.$model" type="date" value-format="yyyy-MM-dd" placeholder="Select Date" />
            </div> -->
            <span slot="footer" class="dialog-footer">
                <el-button @click="showCollectionFeesDialog = false">Cancel</el-button>
                <el-button type="primary" @click="recheckFormCollectionInputs" :disabled="disableSaveCFBtn">Save</el-button>
            </span>
        </el-dialog>

        <el-dialog class="member-cf-dialog" title="Add Collection Fee to a Member" :visible.sync="showMemberCFDialog">
            <div class="form-group">
                <label>Active Member</label>
                <el-select :loading="loadingAllMembersFlag"
                    v-model="$v.formMemberCF.member_id.$model" filterable class="w-100" placeholder="Select Member">
                    <el-option v-for="option in activeMembers" :key="option.id" :value="option.id"
                        :label="option.last_name + ', ' + option.first_name" />
                </el-select>
                <div v-if="$v.formMemberCF.member_id.$dirty">
                    <div class="note color-FF2C2C" v-if="!$v.formMemberCF.member_id.required">Required Field</div>
                </div>
            </div>

            <div class="form-group">
                <label>Active Collection Fee</label>
                <el-select v-model="$v.formMemberCF.collection_fee_id.$model" class="w-100" placeholder="Select Collection Fee">
                    <el-option v-for="option in activeCollectionFees" :key="option.id" :value="option.id"
                        :label="option.payment_type_name + ' ' + (option.description ? '- '+option.description + ' ' : '' ) +(option.year ? '('+option.year+')' + ' ' : '' ) + ( '- ' + option.amount )" />
                </el-select>
                <div v-if="$v.formMemberCF.collection_fee_id.$dirty">
                    <div class="note color-FF2C2C" v-if="!$v.formMemberCF.collection_fee_id.required">Required Field</div>
                </div>
            </div>

            <span slot="footer" class="dialog-footer">
                <el-button @click="showMemberCFDialog = false, loadingAllMembersFlag = false">Cancel</el-button>
                <el-button type="primary" @click="recheckFormMemberCFInputs" :disabled="disableSaveMemberCFBtn">Save</el-button>
            </span>
        </el-dialog>

        <el-dialog class="cf-add-to-all-dialog" title="Add Collection Fee to All Active Members" :visible.sync="showAddCFToAllDialog">
            <div class="form-group">
                <label>Active Collection Fee</label>
                <el-select v-model="$v.formAddCFToAll.collection_fee_id.$model" class="w-100" placeholder="Select Collection Fee">
                    <el-option v-for="option in activeCollectionFees" :key="option.id" :value="option.id"
                        :label="option.payment_type_name + ' ' + (option.description ? '- '+option.description + ' ' : '' ) +(option.year ? '('+option.year+')' + ' ' : '' ) + ( '- ' + option.amount )" />                </el-select>
                <div v-if="$v.formAddCFToAll.collection_fee_id.$dirty">
                    <div class="note color-FF2C2C" v-if="!$v.formAddCFToAll.collection_fee_id.required">Required Field</div>
                </div>
            </div>

            <span slot="footer" class="dialog-footer">
                <el-button @click="showAddCFToAllDialog = false">Cancel</el-button>
                <el-button type="primary" @click="recheckFormAddCFToAllInputes" :disabled="disableSaveAddCFToALlBtn">Save</el-button>
            </span>
        </el-dialog>

        <el-dialog :visible.sync="deleteConfirmationTab">
            <div class="text-center">Are you sure you want to delete this collection fee? All orders, payments, and deposit slips related to this collection fee will also be deleted.</div>
            <div class="text-center mt-3">
                <el-button :loading="is_deleting" :disabled="is_deleting" @click="deleteCollFee()" type="danger" icon="el-icon-delete">Delete</el-button>
                <el-button @click="cancelDelete" type="info">Cancel</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
const _spinner = '<i class="fa fa-spinner fa-spin"></i>';
import moment from 'moment'
import { required, requiredIf, maxLength } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
             pickerOptions: {
                disabledDate(date) {
                return date > Date.now();
                }
            },
            manual_token: "2066|SRE3gQGaZMlV3YLBaAKxwIyAue8l5rMTBxnyDmDR",
            errors: [],
            apiDomain: this.$store.state.apiUrl,
            thisServerUrl: window.location.origin + '/',
            token: '',
            spinner: _spinner,
            tableLoading: false,
            disableSaveCFBtn: false,
            disableSaveMemberCFBtn: false,
            disableSaveAddCFToALlBtn: false,

            showCollectionFeesDialog: false,
            showMemberCFDialog: false,
            showAddCFToAllDialog: false,
            forDeleteID: '',
            is_deleting: false,

            paymentTypes: [],
            statusTypes: [
                { id: 1, name: "Active", value: 1 },
                { id: 2, name: "Inactive", value: 0 },
            ],

            loadingAllMembersFlag: false,
            activeMembers: [],
            activeCollectionFees: [],
            deleteConfirmationTab: false,

            formCollection: {
                id: null,
                description: null,
                payment_type: 1,
                year: "",
                start_period: null,
                end_period: null,
                amount: null,
                status: 1,
                // valid_until: null
            },

            formMemberCF: {
                member_id: null,
                collection_fee_id: null,
            },

            formAddCFToAll: {
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
            payment_type: { 
                required
            },
            year: {
                required: requiredIf((formCollection) => 
                !formCollection.payment_type == 1 || !formCollection.payment_type == 2)
            },
            start_period: {
                required: requiredIf((formCollection) => 
                formCollection.payment_type == 1 || formCollection.payment_type == 2)
            },
            end_period: {
                required: requiredIf((formCollection) => 
                formCollection.payment_type == 1 || formCollection.payment_type == 2)
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
            },
            // valid_until: {
            //     required
            // }
        },
        formMemberCF: {
            member_id: { 
                required
            },
            collection_fee_id: { 
                required
            }, 
        },
        formAddCFToAll: {
            collection_fee_id: { 
                required
            },
        }
    },
    filters: {
        moment: function (date) {
            return moment(date).format('MMMM, YYYY');
        }
    },    
    methods: {
        resetInput() {
        this.formCollection.year = "";
        this.formCollection.start_period = null;
        this.formCollection.end_period = null;
        },
        getPagedCollectionFees(url) {
            if (url) {
                this.tableLoading = true
                axios.get(url)
                .then(res => {
                    if(res.data) {
                        this.collectionFees = res.data;
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
                // // console.log(err)
			});	
        },
        getCollectionFees() {
            this.tableLoading = true
            axios.get(this.apiDomain + '/api/collection-fees/fiscal', {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                    // Authorization: "Bearer " + this.manual_token,
                },
            })
			.then(res => {
                this.tableLoading = false
                // console.log('getCollectionFees res: ', res)
                this.collectionFees = res
			})
			.catch(err => {
                // // console.log(err)
                this.tableLoading = false
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

            this.$confirm('This Collection Fee will not be editable. Continue?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			}).then(() => {
				axios.post(this.apiDomain + `/api/collection-fees`, this.formCollection, {
                    headers: {
                        // Authorization : "Bearer " + this.manual_token
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then(res => {
                    this.$message.success(res.data.message)
                    this.showCollectionFeesDialog = false
                    this.getCollectionFees()
                    this.disableSaveCFBtn = false
                    window.location.reload();
                })
                .catch(err => {
                    this.$message.error("An error occurred. Please contact the site admin.")
                    // console.log('qwew',err.response.data.errors.end_period[0])
                    this.errors = err.response.data.errors
                    this.disableSaveCFBtn = false
                });
			}).catch(() => {
				this.$message({
					type: 'info',
					message: 'Cancelled'
				});
                this.disableSaveCFBtn = false
			});
        },
        getActiveMembers() {
            axios.get(this.apiDomain + "/api/getallmembers", {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
                // // console.log("getActiveMembers res: ", res)
				if(res.data) {
                    this.activeMembers = res.data
                    this.loadingAllMembersFlag = false
                }
			})
			.catch(err => {
                console.error("getActiveMembers error: ", err)
                this.$message.error("Could not get list of Active members.")
			});	
        },
        getActiveCollectionFees() {
            axios.get(this.apiDomain + "/api/collection-fees/active", {
                headers: { "Authorization": "Bearer " + this.token }
            })
			.then(res => {
				if(res.data) {
                    this.activeCollectionFees = res.data
                }
			})
			.catch(err => {
                // // console.log("getActiveCollectionFees error: ", err)
                this.$message.error("Could not get list of Active collection fees.")
			});	
        },
        loadMemberCFDialogData() {
            this.showMemberCFDialog = true
            this.loadingAllMembersFlag = true
            this.getActiveMembers()            
            this.getActiveCollectionFees()
        },
        recheckFormMemberCFInputs() {
            this.$v.formMemberCF.$touch()
            if(this.$v.formMemberCF.$invalid) {
                this.$message.error("Please fill in the required fields properly.")
            } else {
                this.disableSaveMemberCFBtn = true
                this.saveMemberCF()
            }
        },
        saveMemberCF() {
            // // console.log(this.formMemberCF)

            axios.put(this.apiDomain + "/api/collection-fees/member", this.formMemberCF, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            .then(res => {
                this.$message.success(res.data.message)
                this.showMemberCFDialog = false
                this.loadingAllMembersFlag = false
                this.disableSaveMemberCFBtn = false
            })
            .catch(err => {
                // // console.log(err.response)
                if(err.response.status == 400) {
					this.$message.error(err.response.data.message);
				} else {
					this.$message.error("Could not add Collection Fee to Member. Please contact the site admin.")
                }
                
                this.disableSaveMemberCFBtn = false
            });
        },
        loadAddCFToAllDialogData() {
            this.getActiveCollectionFees()
            this.showAddCFToAllDialog = true
        },
        recheckFormAddCFToAllInputes() {
            this.$v.formAddCFToAll.$touch()
            if(this.$v.formAddCFToAll.$invalid) {
                this.$message.error("Please fill in the required fields properly.")
            } else {
                this.disableSaveAddCFToALlBtn = true
                this.saveAddCFToAll()
            }
        },
        saveAddCFToAll() {
            axios.post(this.apiDomain + "/api/collection-fees/member/all", this.formAddCFToAll, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            .then(res => {
                this.$message.success(res.data.message)
                this.showAddCFToALlDialog = false
                this.disableSaveAddCFToALlBtn = false
            })
            .catch(err => {
                // // console.log(err.response)
                if(err.response.status == 400) {
					this.$message.error(err.response.data.message);
				} else {
					this.$message.error("Could not add Collection Fee to All Members. Please contact the site admin.")
                }
                
                this.disableSaveAddCFToALlBtn = false
            });
        },
        deleteConfirmation(id) {
            this.deleteConfirmationTab = true
            this.forDeleteID = id
        },
        cancelDelete() {
            this.deleteConfirmationTab = false
            this.forDeleteID = ''
        },
        deleteCollFee() {
            var id = this.forDeleteID
            this.is_deleting = true
            axios.delete(this.apiDomain + '/api/collection-fees/' + id, {
                headers: { "Authorization": "Bearer " + this.$cookies.get("token") }
            }).then(res => {
                if(res.data) {
                    this.getCollectionFees()
                    this.is_deleting = false
                    this.deleteConfirmationTab = false
                    this.$message.success('The selected collection fee has been deleted.')
                }
            })
            .catch(err => {
                // // console.log(err);
                this.$message.error('Error deleting selected collection fee.')
                this.is_deleting = false
                this.deleteConfirmationTab = false
            })
        },
        editItem(item){
            this.formCollection = item
            item.status = item.status===true ? 1:0
            item.year = String(item.year);
            this.showCollectionFeesDialog = true
        }         
    },
    mounted: function () {
        this.getTokenCookie()
        this.getCollectionFees()
        this.getPaymentTypes()
    } 
}
</script>

<style lang="scss" scoped>
.el-input-number {
    ::v-deep input {
        text-align: left;
    }
}
.error-message {
  background-color: #fce4e4;
  border: 1px solid #fcc2c3;
  padding: 5px 10px;
}

.error-text {
  color: #d84e36;
  font-family: Helvetica, Arial, sans-serif;
  font-size: 13px;
  font-weight: bold;
  line-height: 20px;
  text-shadow: 1px 1px rgba(250,250,250,.3);
}
</style>

<style>
.note {
    font-size: 12px;
}
.color-FF2C2C {
    color: #FF2C2C
}
</style>