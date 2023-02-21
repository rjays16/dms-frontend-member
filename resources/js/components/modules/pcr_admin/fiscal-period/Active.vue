<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="mt-3">
                            <i class="el-icon-s-home"></i>
                            <span> / Fiscal Period / Active List</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9 text-right">
               
                        <el-button size="small" class="mt-2" type="primary" @click="showFiscalPeriod()">
                            Add New
                        </el-button>
                    </div>
                </div>                
            </div>              
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <h1>Fiscal Period</h1>
                    <div class="table-responsive">
                        <el-table
                            v-loading="tableLoading"
                            stripe
                            :data="collectionFees.data"
                            class="member-approved-table"
                            style="width: 100%">
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
                                prop="is_active"
                                label="Status">
                                <template slot-scope="scope">
                                    <div>{{ scope.row.is_active ? 'Active' : 'Inactive' }}</div>
                                </template>
                            </el-table-column>
                            
                            <el-table-column
                                align="center"
                                prop="actions"
                                label="Actions">
                                <template slot-scope="scope">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-4">
                                            <el-button class="w-100 my-1 mx-0" plain type="info" size="mini" @click="review(scope.row.id)" icon="el-icon-view">
                                            </el-button>
                                        </div>
                                        <div class="col-lg-4 col-sm-4">
                                            <el-button class="w-100 my-1 mx-0" plain type="success" size="mini" @click="editItem(scope.row)" icon="el-icon-edit">
                                            </el-button>
                                        </div>
                                        <div class="col-lg-4 col-sm-4">
                                            <el-button class="w-100 my-1 mx-0" plain type="danger" size="mini" @click="deleteConfirmation(scope.row.id)" icon="el-icon-delete">
                                            </el-button>
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>                 
                        </el-table>

                        <div class="block text-center mt-5">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedFiscalPeriod(collectionFees.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in collectionFees.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedFiscalPeriod(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedFiscalPeriod(collectionFees.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>
                    </div>                 
                </div>
            </div>
        </div>

        <el-dialog class="collection-fees-dialog" :visible.sync="showFiscalPeriodDialog" :close-on-click-modal="false">
            <div class="px-5">
            <h4 v-if="clickedSave" class="text-center">Add Fiscal Period</h4>
            <h4 v-else-if="clickedUpdate" class="text-center">Edit Fiscal Period</h4>
            <hr class="mb-5">

            <div class="pb-2" v-if="errors.end_period">
            <div class="error-message">
            <span class="error-text">* {{ errors.end_period[0] }}</span>
            </div>
            </div>

            <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label class="text-center">Start Period</label>
                <el-date-picker class="w-100" v-model="$v.formCollection.start_period.$model" type="month" format="MMMM, yyyy" value-format="yyyy-MM-dd HH:mm:ss" picker-options="pickerOptions" placeholder="From:" />
                <div v-if="$v.formCollection.start_period.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.formCollection.start_period.required">*Required Field</div>
                </div>
            </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label>End Period</label>
                <el-date-picker class="w-100" v-model="$v.formCollection.end_period.$model" type="month" format="MMMM, yyyy" value-format="yyyy-MM-dd HH:mm:ss" picker-options="pickerOptions" placeholder="To:" />
                <div v-if="$v.formCollection.end_period.$dirty">
                <div class="note color-FF2C2C" v-if="!$v.formCollection.end_period.required">*Required Field</div>
                </div>
            </div>
            </div>

            </div>

            <div class="form-group">
                <label>Status</label>
                <el-select v-model="$v.formCollection.is_active.$model" class="w-100" placeholder="Select">
                    <el-option
                        v-for="option in statusTypes"
                        :key="option.id"
                        :label="option.name"
                        :value="option.value" />
                </el-select>
                <div v-if="$v.formCollection.is_active.$dirty">
                    <div class="note color-FF2C2C" v-if="!$v.formCollection.is_active.required">Required Field</div>
                </div>
            </div>
            </div>

            <span slot="footer" class="dialog-footer px-5 mb-5">
                <el-button @click="showFiscalPeriodDialog = false">Cancel</el-button>
                <el-button v-if="clickedSave" type="success" @click="recheckFormFiscalInputs" :disabled="disableSaveCFBtn">Save</el-button>
                <el-button v-if="clickedUpdate" type="success" @click="recheckFormUpdate" :disabled="disableSaveCFBtn">Update</el-button>
            </span>
        </el-dialog>

        <el-dialog class="member-review-dialog text-center px-3" title="View Fiscal Period" :visible.sync="dialogReviewTab">
        <table width="100%" cellspacing="0" cellpadding="7">
            <tr class="text-center">
                <td><span class="font-weight-bold">From: &nbsp;</span><u>{{ showView.start_period | moment }}</u></td>
                <td><span class="font-weight-bold">To: &nbsp;</span><u>{{ showView.end_period | moment }}</u></td>
            </tr>
            <tr class="text-center">
                <td><span class="font-weight-bold">Status: </span></td>
                <td>{{ showView.is_active ? "Active" : "Inactive" }}</td>
            </tr>

        </table>
    </el-dialog>

        <el-dialog :visible.sync="deleteConfirmationTab">
            <div class="text-center">Are you sure you want to delete this fiscal period?</div>
            <div class="text-center mt-3">
                <el-button :loading="is_deleting" :disabled="is_deleting" @click="deleteFiscalPeriod()" type="danger" icon="el-icon-delete">Delete</el-button>
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
            errors: [],
            apiDomain: this.$store.state.apiUrl,
            thisServerUrl: window.location.origin + '/',
            token: '',
            spinner: _spinner,
            tableLoading: false,
            disableSaveCFBtn: false,
            clickedSave: false,
            clickedUpdate: false,

            showFiscalPeriodDialog: false,
        
            forDeleteID: '',
            is_deleting: false,

            paymentTypes: [],
            statusTypes: [
                { id: 1, name: "Active", value: 1 },
                { id: 2, name: "Inactive", value: 0 },
            ],

            activeCollectionFees: [],
            showView: [],
            dialogReviewTab: false,
            deleteConfirmationTab: false,

            formCollection: {
                id: null,
                start_period: null,
                end_period: null,
                is_active: 1,
            },

            collectionFees: {
                data: []
            }
        }
    },
    validations: {
        formCollection: {
            start_period: {
                required
            },
            end_period: {
                required
            },
            is_active: {
                required
            },
        },
    },
    filters: {
        moment: function (date) {
            return moment(date).format('MMMM, YYYY');
        }
    },    
    methods: {
        showFiscalPeriod(){
            this.clickedUpdate = false
            this.clickedSave = true
            this.showFiscalPeriodDialog = true
        },
        getPagedFiscalPeriod(url) {
            if (url) {
                this.tableLoading = true
                axios.get(url)
                .then(res => {
                    if(res.data) {
                        this.collectionFees = res.data;
                        console.log('qwe',this.collectionFees)
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
        getFiscalPeriod() {
            this.tableLoading = true
            axios.get(this.apiDomain+ '/api/fiscal-period', {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                this.tableLoading = false
                // console.log('getFiscalPeriod res: ', res.data.data)
                this.collectionFees = res.data
			})
			.catch(err => {
                // // console.log(err)
                this.tableLoading = false
			});	
        },
        review (id) {
            this.dialogReviewTab = true
            // this.is_approving = true
            axios.get(this.apiDomain + '/api/fiscal-period/' + id, {
                headers: { "Authorization": "Bearer " + this.$cookies.get("token") }
            }).then(response => {
				if(response.data) {
                    this.showView = response.data
                    // console.log('review',response.data)
                }
			})
			.catch(error => {
                console.log(error);
                // this.is_approving = false
			});	            
        },
        recheckFormFiscalInputs() {
            this.$v.formCollection.$touch()
            if(this.$v.formCollection.$invalid) {
                this.$message.error("Please fill in the required fields properly.")
            } else {
                this.disableSaveCFBtn = true
                this.saveFiscalPeriod()
            }
        },
        recheckFormUpdate() {
            this.$v.formCollection.$touch()
            if(this.$v.formCollection.$invalid) {
                this.$message.error("Please fill in the required fields properly.")
            } else {
                this.disableSaveCFBtn = true
                this.updateFiscalPeriod()
            }
        },
        saveFiscalPeriod() {
            // // console.log(this.formCollection)

            this.$confirm('Continue adding this fiscal period?', 'Warning', {
				confirmButtonText: 'OK',
				cancelButtonText: 'Cancel',
				type: 'warning'
			}).then(() => {
				axios.post(this.apiDomain + `/api/fiscal-period`, this.formCollection, { headers: {Authorization: "Bearer " + this.$cookies.get("token"), }, })
                .then(res => {
                    this.$message.success(res.data.message)
                    this.showFiscalPeriodDialog = false
                    this.getFiscalPeriod()
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
        updateFiscalPeriod() {
                axios.put(this.apiDomain + `/api/fiscal-period/update/${this.formCollection.id}`, this.formCollection, {
                    headers: { Authorization: "Bearer " + this.$cookies.get("token") }
                })
                .then(res => {
                    // console.log(response)
                    this.$message.success(res.data.message)
                    this.showFiscalPeriodDialog = false
                    this.getFiscalPeriod()
                    this.disableSaveCFBtn = false
                    window.location.reload();
                })
                .catch(err => {
                    // console.log(err.response)
                    // console.log(err)
                    this.$message.error("Fiscal period details could not be updated. Please try again.")
                    this.errors = err.response.data.errors
                    this.disableSaveCFBtn = false
                })

        },
        deleteConfirmation(id) {
            this.deleteConfirmationTab = true
            this.forDeleteID = id
        },
        cancelDelete() {
            this.deleteConfirmationTab = false
            this.forDeleteID = ''
        },
        deleteFiscalPeriod() {
            var id = this.forDeleteID
            this.is_deleting = true
            axios.delete(this.apiDomain + '/api/fiscal-period/delete/' + id, {
                headers: { "Authorization": "Bearer " + this.$cookies.get("token") }
            }).then(res => {
                this.is_deleting = false
                this.deleteConfirmationTab = false
                this.getFiscalPeriod()
                this.$message.success(res.data.message)
            })
            .catch(err => {
                this.$message.error(err.response.data.message)
                this.is_deleting = false
                this.deleteConfirmationTab = false
            })
        },
        editItem(item){
            this.clickedSave = false
            this.clickedUpdate = true
            this.formCollection = item
            // this.formCollection.start_period = item.start_period
            // this.formCollection.end_period = item.end_period

            item.is_active = item.is_active===true ? 1:0

            // this.formCollection.is_active = item.is_active
            // item.start_period = moment(item.start_period).format('YYYY-MM-DD HH:mm:ss');
            // item.end_period = moment(item.end_period).format('YYYY-MM-DD HH:mm:ss');
            // console.log(item.end_period);
            // console.log(item.start_period);
            this.showFiscalPeriodDialog = true
        }         
    },
    mounted: function () {
        this.getTokenCookie()
        this.getFiscalPeriod()
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
.dot {
  height: 10px;
  width: 10px;
  background-color: rgb(7, 167, 7);
  border-radius: 50%;
  display: inline-block;
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