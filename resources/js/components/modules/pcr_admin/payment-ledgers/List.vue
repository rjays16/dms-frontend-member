<template>
    <div>
        <div class="content-wrapper">
            <div class="admin-page-title bg-white p-3">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mt-2">
                            <i class="el-icon-s-home"></i>
                            <span> / Payment Ledgers / List</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 text-right">
                        <el-button size="small" type="primary" @click="exportPayments()" class="btn-export mt-2">
                            Export
                        </el-button>
                    </div>
                </div>
            </div>
            <div class="container-fluid pb-5">
                <div class="admin-conent bg-white my-5 mx-3 p-4">
                    <h1>Payment Ledgers</h1>

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
                        <div v-if="noSearchRes" class="col-sm-12 col-md-12 text-center">
                            <div class="show-search-res">
                                <span class="show-search-res-txt">No Results Found</span>
                                <el-button class="ml-2" size="mini" type="info" :loading="tableLoading" @click="resetSearchResults">Reset</el-button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                      
                        <el-table
                            v-loading="tableLoading"
                            stripe
                            :data="paymentLedgers.data"
                            class="member-approved-table pay-ledg-tbl"
                            :default-sort = "{prop: 'date_paid', order: 'descending'}"
                            style="width: 100%">
                        <el-table-column
                            prop="pcr_member.last_name"
                            label="Name"
                            sortable
                            width="250">
                            <template slot-scope="scope">
                                <div class="table-user-name">{{ scope.row.pcr_member.last_name }}, {{ scope.row.pcr_member.first_name }}</div>
                            </template>                            
                        </el-table-column>
                        <el-table-column
                            prop="amount"
                            sortable
                            label="Amount"> 
                        </el-table-column>                              
                        <el-table-column
                            prop="order_id"
                            sortable
                            label="Order Type"> 
                            <template slot-scope="scope">
                                <div class="table-user-name">{{ scope.row.order.order_type_name }}</div>
                            </template>
                        </el-table-column>
                        <el-table-column
                            prop="payment_method"
                            sortable
                            label="Payment Method">
                            <template slot-scope="scope">
                                <div v-if="scope.row.payment_method == 1">Ideapay</div>
                                <div v-if="scope.row.payment_method == 2">Bank</div>
                                <div v-if="scope.row.payment_method == 3">Check</div>
                            </template>                            
                        </el-table-column>  
                        <el-table-column
                            align="center"
                            sortable
                            label="Status"> 
                            <template slot-scope="scope">
                                <div class="table-user-name">{{ scope.row.order.order_status_name }}</div>
                            </template>
                        </el-table-column> 
                        <el-table-column
                            prop="date_paid"
                            sortable
                            align="center"
                            label="Date Paid">
                            <template slot-scope="scope">
                                <div>{{ scope.row.date_paid | moment }}</div>
                            </template>
                        </el-table-column>
                        </el-table>

                        <div class="block text-center mt-5" v-if="showPaginate">
                            <ul id="member-pages" class="pcr-pagination">
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedPayments(paymentLedgers.first_page_url)" size="mini" plain type="primary">
                                        First
                                    </el-button>                                    
                                </li>
                                <li class="mx-1" v-for="page in paymentLedgers.links" :key="page.label">
                                    <el-button class="px-2" @click="getPagedPayments(page.url)" size="mini" :plain="page.active ? false : true" type="primary">
                                        {{ paginLabel(page.label) }}
                                    </el-button>
                                </li>
                                <li class="mx-1">
                                    <el-button class="px-1" @click="getPagedPayments(paymentLedgers.last_page_url)" size="mini" plain type="primary">
                                        Last
                                    </el-button>                                    
                                </li>                                
                            </ul>
                        </div>
                    </div>                 
                </div>
            </div>
        </div>
        <el-dialog v-if="currentLedger" class="payment-ledger-dialog" title="Review Ledger" :visible.sync="dialogPaymentLedger">
             <table width="100%" cellspacing="0" cellpadding="7">       
                <tr>
                    <td width="30%"><span class="font-weight-bold">Order: </span></td>
                    <td width="70%">{{ currentLedger.order_status_name }}</td>
                </tr> 
                <tr>
                    <td width="30%"><span class="font-weight-bold">Amount: </span></td>
                    <td width="70%">{{ currentLedger.amount }}</td>
                </tr> 
                <tr>
                    <td width="30%"><span class="font-weight-bold">Status: </span></td>
                    <td width="70%">{{ currentLedger.date_paid ? "Paid": "Pending" }}</td>
                </tr> 
                <tr>
                    <td width="30%"><span class="font-weight-bold">Date Paid: </span></td>
                    <td width="70%">{{ currentLedger.date_paid | moment }}</td>
                </tr>  
            </table>
        </el-dialog>
    </div>
</template>

<script>
const _spinner = '<i class="fa fa-spinner fa-spin"></i>';
import moment from "moment";

export default {
    data() {
        return {
            apiDomain: this.$store.state.apiUrl,
            thisServerUrl: window.location.origin + "/",
            token: "",
            spinner: _spinner,  
            tableLoading:true,
            dialogPaymentLedger:false,
            currentLedger:null,
            paymentLedgers: [],
            searchTerm: '',
            showSearchRes: false,
            noSearchRes: false,
            showPaginate: false
        }
    },

    filters: {
        moment: function (date) {
            return moment(date).format('MMM DD YYYY');
        }
    }, 

    methods: {
        getPaymentLedgers() {
            this.tableLoading = true
            axios.get(this.apiDomain + '/api/payments', {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                this.tableLoading = false
                // console.log('getPaymentLedger res: ', res)
                // console.log(res)
                this.paymentLedgers = res.data
                this.showPaginate = true
			})
			.catch(err => {
                // console.log(err)
                this.tableLoading = false
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
        review(id){
            const ledgers = this.paymentLedgers.data
            this.currentLedger = ledgers.filter(item => item.id === id)[0]
            // console.log(id)
            // console.log(ledgers[id])
            this.dialogPaymentLedger = true
        },
        getSearchResults() {
            this.tableLoading = true
            axios.get(this.apiDomain + `/api/payments/search?keyword=${this.searchTerm}`, {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token")
                }
            })
			.then(res => {
				if(res.data) {
                    this.paymentLedgers.data = res.data
                    // console.log(res)
                    this.tableLoading = false
                    this.showSearchRes = true
                }
			})
			.catch(err => {
                // console.log(err.message)
                this.tableLoading = false
                this.showSearchRes = false
                this.noSearchRes = true
                this.paymentLedgers.data = []
                this.showPaginate = false
			});		
        },
        clearSearchResults () {
            this.searchTerm = ''
            this.showSearchRes = false
            this.getPaymentLedgers()
        },
        resetSearchResults () {
            this.searchTerm = ''
            this.noSearchRes = false
            this.getPaymentLedgers()
        },
        getPagedPayments(url) {
            if (!url) {

            } else {
                this.tableLoading = true
                axios.get(url, {
                    headers: { "Authorization": "Bearer " + this.token }
                })
                .then(res => {
                    if(res.data) {
                        this.paymentLedgers = res.data
                        //// console.log(res)
                        this.tableLoading = false
                        this.showPaginate = true
                    }
                })
                .catch(err => {
                    // console.log(err)
                    this.tableLoading = true
                });	
            }
        },  
        exportPayments() {
            $('.btn-export').prop('disabled', true)
            axios.get(this.apiDomain + '/api/payments/export', {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
			.then(res => {
                const url = URL.createObjectURL(new Blob([res.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', "payments.csv")
                document.body.appendChild(link)
                link.click()
                document.body.removeChild(link)
				$('.btn-export').prop('disabled', false)
			})
			.catch(err => {
                // console.log(err)
                this.$message.error('Could not export payments. Please contact the site admin.')
                $('.btn-export').prop('disabled', false)
			});	
        }       
    },
    mounted() {
        this.getTokenCookie() 
        this.getPaymentLedgers()

        // setTimeout(() => {
        //     this.tableLoading = false
        // }, 1000);
    }  
};
</script>

<style>

    .pay-ledg-tbl th .cell {
        white-space: nowrap;
    }

</style>
