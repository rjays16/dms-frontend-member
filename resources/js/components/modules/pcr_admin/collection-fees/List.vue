<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-3">
                        <div class="mt-3">
                            <i class="el-icon-s-home"></i>
                            <span> / Collection Fees / List</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9 text-right">
                        <el-button size="small" class="my-2" type="primary" @click="openAddToAllDialog()">
                            Apply Collection to All Active Members
                        </el-button>
                        <el-button size="small" class="my-2" type="primary" @click="openAddToMemberDialog()">
                            Apply Collection to a Specific Active Member
                        </el-button>
                        <el-button size="small" class="my-2" type="primary" @click="openCollectionFeesDialog()">
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
                            <!-- <el-table-column
                                prop="year"
                                label="Year">
                            </el-table-column> -->
                            <el-table-column
                                prop="description"
                                label="Description">
                            </el-table-column>
                            <el-table-column
                                prop="amount"
                                label="Amount">
                            </el-table-column>
                            <el-table-column
                                prop="period"
                                label="Period">
                                <template slot-scope="scope">
                                    <div class="text-uppercase">
                                        {{ scope.row.fiscal_period ? fiscalMoment(scope.row.fiscal_period.start_period) + ' - ' + fiscalMoment(scope.row.fiscal_period.end_period)
                                            : scope.row.year ? scope.row.year : '' }}
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column
                                prop="status"
                                label="Status">
                                <template slot-scope="scope">
                                    <div class="text-uppercase">{{ scope.row.status === 0 ? 'Inactive' : 'Active' }}</div>
                                </template>
                            </el-table-column>
                            <!-- <el-table-column
                                prop="valid_until"
                                label="Valid Until">
                            </el-table-column> -->
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

        <el-dialog :visible.sync="deleteConfirmationTab">
            <div class="text-center">Are you sure you want to delete this collection fee? All orders, payments, and deposit slips related to this collection fee will also be deleted.</div>
            <div class="text-center mt-3">
                <el-button :loading="is_deleting" :disabled="is_deleting" @click="deleteCollFee()" type="danger" icon="el-icon-delete">Delete</el-button>
                <el-button @click="cancelDelete" type="info">Cancel</el-button>
            </div>
        </el-dialog>

        <AddEditCollectionFee ref="addEditModal" :token="token" :apiDomain="apiDomain" />
        <AddCollectionFeeToMember ref="addToMemberModal" :token="token" :apiDomain="apiDomain" />
        <AddCollectionFeeToAll ref="addToAllModal" :token="token" :apiDomain="apiDomain" />
    </div>
</template>

<script>
const _spinner = '<i class="fa fa-spinner fa-spin"></i>';
import moment from 'moment'
import { required } from 'vuelidate/lib/validators'
import AddEditCollectionFee from "../../pcr_admin/modals/CollectionFee/AddEdit.vue"
import AddCollectionFeeToMember from "../../pcr_admin/modals/CollectionFee/AddToMember.vue"
import AddCollectionFeeToAll from "../../pcr_admin/modals/CollectionFee/AddToAll.vue"
export default {
    components: {
        AddEditCollectionFee,
        AddCollectionFeeToMember,
        AddCollectionFeeToAll
    },
    data() {
        return {
            errors: [],
            apiDomain: this.$store.state.apiUrl,
            thisServerUrl: window.location.origin + '/',
            token: '',
            spinner: _spinner,
            tableLoading: false,
            disableSaveAddCFToALlBtn: false,
            
            forDeleteID: '',
            is_deleting: false,
            
            deleteConfirmationTab: false,            

            collectionFees: {
                data: []
            },

            is_editing_fee: false,            
        }
    },
    validations: {
        formAddCFToAll: {
            collection_fee_id: { 
                required
            },
        }
    },
    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD YYYY');
        },
    },    
    methods: {
        getPagedCollectionFees(url) {
            if (url) {
                this.tableLoading = true
                axios.get(url)
                .then(res => {
                    this.collectionFees = res.data;
                    //// console.log(this.members)
                    this.tableLoading = false
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
        getCollectionFees() {
            this.tableLoading = true
            axios.get(this.apiDomain + '/api/collection-fees', {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                this.tableLoading = false
                // console.log('getCollectionFees res: ', res)
                this.collectionFees = res.data
			})
			.catch(err => {
                // console.log(err)
                this.tableLoading = false
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
                this.getCollectionFees()
                this.is_deleting = false
                this.deleteConfirmationTab = false
                this.$message.success('The selected collection fee has been deleted.')
            })
            .catch(err => {
                // console.log(err);
                this.$message.error('Error deleting selected collection fee.')
                this.is_deleting = false
                this.deleteConfirmationTab = false
            })
        },
        openCollectionFeesDialog() {
            this.$refs.addEditModal.openModal(false)            
        },
        editItem(item) {
            this.$refs.addEditModal.openModal(true, item) 
        },        
        openAddToMemberDialog() {
            this.$refs.addToMemberModal.openModal()
        },        
        openAddToAllDialog() {
            this.$refs.addToAllModal.openModal()
        },
        fiscalMoment(date) {
            return moment(date).format('MMMM, YYYY');
        }
    },
    mounted() {
        this.getTokenCookie()
        this.getCollectionFees()
    } 
}
</script>

<style lang="scss" scoped>
.el-input-number {
    ::v-deep input {
        text-align: left;
    }
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