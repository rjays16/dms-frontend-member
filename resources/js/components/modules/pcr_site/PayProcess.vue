<template>
	<div class="pay-process-dialog">
        <el-table
            :data="annualOrders"
            stripe
            class="mb-5"
            v-loading="listLoading"
            style="width: 100%">
            <el-table-column
                prop="collection_fee.description"
                label="Description">
                <template slot-scope="scope">
                    <p class="mb-2 wb-normal font-italic">
                        <span class="mob-col-desc font-weight-bold">Description: </span>
                        <span v-if="scope.row.collection_fee.description === 'Good Standing Certificate Fee'">
                            <p class="mb-0">Good Standing Certificate Request</p>
                        </span>
                        <span v-else-if="scope.row.collection_fee.description === 'Platform Computerization Fee'">
                            <p><u>Computerization Fee</u></p>
                            <p>One-Time Payment</p>
                        </span>
                        <span v-else>
                            {{ scope.row.collection_fee.description }}
                        </span>
                        {{ scope.row.collection_fee.fiscal_period ? '('+fiscalMoment(scope.row.collection_fee.fiscal_period.start_period) + ' - ' + fiscalMoment(scope.row.collection_fee.fiscal_period.end_period)+')'
                            : scope.row.collection_fee.year ? '('+scope.row.collection_fee.year+')' : '' }}
                    </p>
                </template>
            </el-table-column>
            <el-table-column
                prop="amount"
                label="Order Amount">
                <template slot="header" slot-scope="scope">
                    <span class="d-none">{{ scope.row }}</span>
                    <span>Order<br>Amount</span>
                </template>                    
                <template slot-scope="scope">
                    <span class="mob-col-desc font-weight-bold">Order Amount: </span>
                    Php. {{ formatDecimal(scope.row.amount, 2 ) }}
                </template>
            </el-table-column>
            <el-table-column
                prop="order_payments"
                label="Total Payment">
                <template slot="header" slot-scope="scope">
                    <span class="d-none">{{ scope.row }}</span>
                    <span>Total<br>Payment</span>
                </template>                      
                <template slot-scope="scope">
                    <span class="mob-col-desc font-weight-bold">Total Payment: </span>
                    Php. {{ formatDecimal(scope.row.order_payments, 2) }}
                </template>
            </el-table-column>
            <el-table-column
                label="Transaction Fee">
                <template slot="header" slot-scope="scope">
                    <span class="d-none">{{ scope.row }}</span>
                    <span>Transaction<br>Fee</span>
                </template>
                <template slot-scope="scope">
                    <span class="mob-col-desc font-weight-bold">Transaction Fee: </span>
                    {{ scope.row.convenience_fee }}
                </template>
            </el-table-column>            
            <el-table-column
                prop="actions"
                label="">
                <template slot-scope="scope">
                    <div class="ipay-btn text-center">
                        <div class="mb-2" v-if="scope.row.status === 3">
                            <!-- <div v-if="scope.row.payment && scope.row.payment.deposit_slip_id">
                                <el-button class="w-100 pending-btn" size="mini" round>
                                    Deposit Slip is pending for approval
                                </el-button>
                            </div>
                            <div v-else> -->
                                <el-button class="w-100 failed-pay-btn mb-2" size="mini" @click="openIdeapay(scope.row)" round>
                                    Previous Payment Failed. Click here to try again.
                                </el-button>
                                <div v-if="showDepSlipBtn(scope.row)">
                                    <el-button class="w-100 ws-normal wb-normal" type="primary" size="mini" @click="showDepositDialog(scope.row)" round>
                                        Upload Deposit Slip
                                    </el-button>
                                </div>
                            <!-- </div> -->
                        </div>
                        <div v-else>
                            <div class="mb-2">
                                <el-button class="w-100 ws-normal wb-normal" :type="buttonType(scope.row.status)" size="mini" round @click="openIdeapay(scope.row)">
                                    {{ buttonLabel(scope.row.status)}}
                                </el-button>
                            </div>
                            <div v-if="showDepSlipBtn(scope.row)">
                                <el-button class="w-100 ws-normal wb-normal" type="primary" size="mini" @click="showDepositDialog(scope.row)" round>
                                    Upload Deposit Slip
                                </el-button>
                            </div>
                            <div v-if="scope.row.status === 2 && scope.row.collection_fee.description === 'Good Standing Certificate Fee' && scope.row.collection_fee.fiscal_period">
                                <el-button v-if="!has_requested_cogs" class="w-100 ws-normal wb-normal" type="primary" size="mini" round
                                    @click="sendCertificateRequest(scope.row)" :disabled="is_sending_cert_request" :loading="is_sending_cert_request">
                                    Send Request for Certificate
                                </el-button>
                                <el-button v-else class="w-100 ws-normal wb-normal" type="success" size="mini" round>
                                    Successfully Sent Request for Certificate
                                </el-button>
                            </div>
                        </div>
                    </div>
                </template>
            </el-table-column>
        </el-table> 

        <el-dialog title="Upload Deposit Slip" :visible.sync="depositDialog" width="60%">
            <div class="mb-4">
                <p class="font-weight-bold">Enter Amount Paid:</p>
                <el-input-number v-model="depslipAmnt" :min="0" :max="maxAmount"></el-input-number>
                <div class="max-amnt-msg">Your payment should be exactly Php. {{ formatDecimal(maxAmount,2) }}.</div>
            </div>                
            <div class="mb-2 wb-normal" style="font-weight:bold;">Select your deposit slip image by clicking the box below: </div>
            <el-upload
                class="avatar-uploader ava-icon-square"
                action="/"
                :http-request="addAttachment3"
                :auto-upload="false"
                :show-file-list="false"
                :on-success="handleDepositSuccess"
                :before-upload="beforeAvatarUpload"
                :on-change="handleImageUpload3"
                accept="image/png, image/jpeg, image/jpg"
                :file-list="attachments3">
                <img v-if="imageUrl3" :src="imageUrl3" class="avatar"/>
                <i v-else class="el-icon-camera-solid avatar-uploader-icon"></i>
            </el-upload>

            <div class="mt-3 font-italic" style="font-size:10px; !important">
                <el-button
                    class="upload-btn mt-2"
                    type="success"
                    round
                    size="small"
                    @click="uploadDepositSlip()"
                    :disabled="!image3_change"
                    :loading="uploadingNow">
                    Upload Now
                </el-button>                    
            </div>
        </el-dialog>            

        <el-dialog :visible.sync="payLinkDialog" custom-class="pay-link-dialog">
            <div v-if="payLinkUrl" class="font-weight-bold" >
                <p class="wb-normal">In case the Payment portal does not open in a new tab, you can use the link below:</p>
                <a :href="payLinkUrl">{{ payLinkUrl }}</a>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="payLinkDialog = false">OK</el-button>
            </span>
        </el-dialog>    

        <Certificate
            @closeCertDialog="certDialog = false"
            :userID="userID"
            :showDialog="certDialog"
            :pcr_num="pcr_num"
            :pma_num="pma_num"
            :last_name="last_name"
            :memberName="memberName"
            :memberShip="memberShip"
            :memberTypes="memberTypes"
            :tokenValue="tokenValue"
            :order="order">
        </Certificate>
	</div>
</template>

<script>
import { formatDecimal } from "../../../functions.js";
import moment from 'moment'

export default {
    props: ["showDialog", "memberName", "tokenValue", "userID", "annualOrders", "pcr_num", "pma_num" , "last_name", "cert_name", "memberShip", "memberTypes", "has_requested_cogs"],
    data() {
        return {
            apiDomain: this.$store.state.apiUrl,
            ideapayFee: "38.00",
            depositDialog: false,
            imageUrl3: "",
            image_types: ["image/png", "image/jpg", "image/jpeg"],
            file_max: 10000000,
            payLinkDialog: false,
            payLinkUrl: "",
            attachments3: [],
            imageUrl3final: {},
            image3: {},
            image3_change: false,
            uploadingNow: false,
            member_id: this.$cookies.get("member_id"),
            depslipAmnt: 0,
            maxAmount: 0,
            listLoading: false,
            certDialog: false,
            user_id: null,
            order: null,
            is_sending_cert_request: false
        }
    },
    methods: {
        formatDecimal(value, places) {
            return formatDecimal(value, places);
        },
        handleClose() {
            this.$emit('closePayNowDialog', false)
        },
        buttonType(statusId){
            if (statusId === 1) { return "primary" }
            else if (statusId === 2) { return "success" }
            else if (statusId === 3) { return "warning" }
            else if (statusId === 'Claimed') { return "primary" }
            else { return "warning" }
        },
        buttonLabel(statusId){
            if (statusId === 1) { return "Pay" }
            else if (statusId === 2) { return "Paid" }
            else if (statusId === 3) { return "Failed. Click to Retry" }
            else if (statusId === 'Claimed') { return "Pay" }
            else { return "Pending" }
        },
        openIdeapay(order) {
            let data = {
                order_id: order.id
            }            
            axios.put(this.apiDomain + '/api/ideapay/create', data)
            .then(response => {
                // console.log(response)
                this.payLinkUrl = response.data.payment_url
                this.payLinkDialog = true
                window.open(response.data.payment_url, '_blank')
            })
            .catch(error => {
                // console.log(error.response)
                this.$message.error(error.response.data.message)
            });           
		},
		payOrder(order_fee){
            axios.post(this.apiDomain + '/api/fees/order', {'user_id': order_fee.User_ID, 'collection_fee_id': order_fee.id},
            {
                headers: {
                    Authorization: "Bearer " + this.$cookies.get("token"),
                },
            })
            // axios.put(this.apiDomain + '/api/ideapay/create', data)
            .then(response => {
                // console.log(response)
                this.payLinkUrl = response.data.payment_url
                this.payLinkDialog = true
                // this.order_id = response.data.order_id
                window.open(response.data.payment_url, '_blank')
            })
            .catch(error => {
                // console.log(error.response)
                this.$message.error('Error. Please try again.')
            });                     
		},
        showDepositDialog(order) {
            this.maxAmount = order.collection_fee.amount - order.order_payments
            if (this.maxAmount > 0) {
                this.depositDialog = true
                this.order_id = order.id
            } else {
                this.$message.error("No more payments can be made for this order.");
            }
        },     
        reloadInfo(order) {
            if(order.transaction === null) {
                this.$message.error("Transaction for this order was not found. Please contact the site admin.")
            } else {
                // console.log("Ideapay Payment Ref: " + order.transaction.ideapay.payment_ref)
                let data = {
                    payment_id: order.transaction.ideapay.payment_ref
                }

                axios.post(this.apiDomain + '/api/ideapay/verify', data, {
                    headers: {
                        Authorization: "Bearer " + this.$cookies.get("token"),
                    },
                })
                .then(res => {
                    this.$message.success(res.data.message)
                    window.location.reload()
                })
                .catch(err => {
                    // console.log(err)
                    this.$message.error("Could not verify payment. Please contact the site admin.")
                });
            }
        },
        addAttachment3(file, fileList) {
            this.attachments3.push(file);
        },
        handleDepositSuccess(file) {
            // console.log(file);
            this.imageUrl3 = URL.createObjectURL(file.raw);
        },
        beforeAvatarUpload(file) {
            const isPIC = file.type === "image/jpeg";
            const isLt2M = file.size / 1024 / 1024 < 2;

            if (!isPIC) {
                this.$message.error("Profile photo must be an image filetype.");
            }
            if (!isLt2M) {
                this.$message.error("Profile photo cannot exceed 2MB.");
            }
        },
        handleImageUpload3(f, filelist) {
            //this.fullscreenLoading = true;
            // STORES IMAGE IN FILE INPUT THEN STORES THE NAME OF THE FILE IN THE TEXT INPUT FOR VALIDATION
            let file = f;
            this.validateFile(file.raw, "photo", (result) => {
                //this.fullscreenLoading = false;
                if (!result) {
                    file = null;
                }

                this.imageUrl3 = file.raw;
                this.image3_change = true;
                if (this.imageUrl3) {
                    var reader = new FileReader();
                    // this.$v.image3.$model = this.imageUrl3
                    this.image3 = this.imageUrl3;

                    reader.onload = (e) => {
                        this.imageUrl3 = e.target.result;
                    };
                    reader.readAsDataURL(this.imageUrl3);
                } else {
                    this.image3 = "";
                }
            });
            // this.step3.imageUrl3final = f.raw
            this.imageUrl3final = f.raw;
        },
        uploadDepositSlip() {
            if(!this.imageUrl3final) {
                this.$message.error("Please select an image file to upload.");
            } else if(!this.depslipAmnt) {
                 this.$message.error("Please enter the amount paid.");
            } else {
                this.uploadingNow = true;
                let formBody = new FormData();
                formBody.append("deposit_slip", this.imageUrl3final);
                formBody.append("amount", this.depslipAmnt);
                formBody.append("order_id", this.order_id);
                axios.post(this.apiDomain + `/api/pcr/upload/deposit/${this.userID}`, formBody, {
                    headers: { Authorization: "Bearer " + this.$cookies.get("token"), },
                })
                .then((res) => {
                    this.$message.success(res.data.message);
                    this.uploadingNow = false;
                    this.image3_change = false;
                    this.depslipAmnt = 0;
                    this.depositDialog = false;
                    this.imageUrl3 = "";
                    this.$emit('refreshHistory', true);
                })
                .catch((err) => {
                    // console.log(err);
                    this.$message.error(err.response.data.message);
                    this.uploadingNow = false;
                    this.depslipAmnt = 0;
                    this.depositDialog = false;
                    this.imageUrl3 = "";
                    this.$emit('refreshHistory', true)
                });                
            }
        },
        validateFile(file, type, callback) {
            // VALIDATE IMAGE FILE
            let valid_types = this.image_types;
            if (!file) {
                callback(false);
            } else if (file.size > this.file_max) {
                this.$message.error("File is too big!");
                callback(false);
            }
            //   else if(!valid_types.includes(file.type)) {
            //     this.$message.error('Invalid file type');
            //     callback(false)
            //   }
            else {
                callback(true);
            }
        },
        showPayBtn(status) {
            if (status === 3) {
                return false
            } else if (status === 4) {
                return false
            } else {
                return true
            }
        },
        showDepSlipBtn(row) {
            //console.log("status? " + row.status)
            if(row.status === 2) {
                return false
            } else {
                //console.log("enable button? " + row.collection_fee.enable_deposit_slip)
                if(row.collection_fee.enable_deposit_slip === true) {
                    return true
                } else {
                    return false
                }
            }
        },
        showCertDialog(order){
            this.order = order
            this.certDialog = true
        },
        sendCertificateRequest() {
            this.is_sending_cert_request = true
            axios.post(this.apiDomain + '/api/certificate/request', {}, {headers: {Authorization: "Bearer " + this.$cookies.get("token")}})
            .then(res => {
                this.$message.success(res.data.message)
                this.is_sending_cert_request = false
                setTimeout(() => {
                    window.location.reload()
                }, 1000);
            })
            .catch(err => {
                console.error(err)
                this.$message.error(err.response.data.message)
                this.is_sending_cert_request = false
            });
        },
        fiscalMoment(date) {
            return moment(date).format('MMMM, YYYY');
        }
	}
}
</script>

<style>
    .pay-process-dialog .ipay-btn {
        text-align: right;
    }
    .pay-process-dialog .mob-col-desc {
        display: none;
    }
    .pay-process-dialog .avatar-uploader img {
        max-width: 100%;
    }
	.pay-process-dialog .avatar-uploader .el-upload {
		cursor: pointer;
		position: relative;
		overflow: hidden;
	}
	.pay-process-dialog .avatar-uploader .el-upload:hover {
		background: #409eff;
	}
	.pay-process-dialog .avatar-uploader-icon {
		font-size: 28px;
		color: #8c939d;
		width: 100px;
		height: 100px;
		line-height: 100px;
		text-align: center;
		background: #dddddd;
	}
	.pay-process-dialog .avatar-uploader .el-upload:hover .avatar-uploader-icon {
		color: #ffffff;
	}

    .pay-process-dialog .failed-pay-msg {
        font-size:12px;
        color: #b40007;
        font-weight: bold;
    }
    .pay-process-dialog .failed-pay-btn {
        white-space: normal;
        word-break: normal;
        background: #be7d1c!important;
        border: 1px solid #be7d1c!important;
        color: white;
    }
    .pay-process-dialog .failed-pay-btn span {
        line-height: 1rem;
    }
    .pay-process-dialog .pending-btn {
        white-space: normal;
        word-break: normal;
        background: #ffe607!important;
        border: 1px solid #ffe607!important;
        color: black;
    }
    .pay-process-dialog .pending-btn span {
        line-height: 1rem;
    }
    .max-amnt-msg {
        font-style: italic;
        font-size: 12px;
        margin-top:10px;
    }
    .pay-link-dialog {
        width: 50%;
    }
    .ws-normal {
        white-space: normal;  
    }
    .wb-normal {
        word-break: normal;
    }

	@media only screen and (max-width: 767px) {
        .pay-process-dialog .el-table__header {
            display: none;
        }
        .pay-process-dialog table,
        .pay-process-dialog table tr,
        .pay-process-dialog table td,
        .pay-process-dialog table tbody,
        .pay-process-dialog table colgroup,
        .pay-process-dialog table colgroup col {
            width: 100%!important;
            display: block;
            border:none;
        }
        .pay-process-dialog table {
            border-bottom: 3px solid rgba(0,0,0,0.2);
        }
        .pay-process-dialog table tr {
            border-top: 2px solid rgba(0,0,0,0.2);
            border-left: 2px solid rgba(0,0,0,0.2);
            border-right: 2px solid rgba(0,0,0,0.2);

        }
        .pay-process-dialog table {
            table-layout: auto;
        }
        .pay-process-dialog .ipay-btn {
            text-align: center;
        }
        .pay-process-dialog .ipay-btn button {
            width: 50%;
        }
        .pay-process-dialog .mob-col-desc {
            display: inline;
        }
        .pay-link-dialog {
            width: 90%;
        }        
	}

</style>