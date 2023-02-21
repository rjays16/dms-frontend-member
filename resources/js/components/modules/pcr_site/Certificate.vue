<template>
    <div class="cert-dialog">
        <el-dialog        
            title=""
            :visible.sync="showDialog"
            width="70%"
            :before-close="handleClose">
        <div class="text-right mt-5 mb-4">
            <el-button class="btn btn-export" type="primary" @click="generateCertificate(tokenValue, order)"
                :loading="is_exporting"
                :disabled="is_exporting">
                {{ exportText }}
            </el-button>
        </div>

        <div class="pdf-cert-box pt-4 px-4 outline-none" id="pdf-cert-box">
            <section slot="pdf-content" id="pdf-content">
                <div class="certi-cont">
                    <div class="row border-bottom-2">
                        <div class="col-2 outline-none border-0 bs-0">
                            <img src="/images/layout/logo-pma.png" class="header-logo outline-none border-0 bs-0" />
                        </div>
                        <div class="col-8 text-center">
                            <div class="pt-5">
                                <h4 class="mb-1 text-uppercase times-new-roman color-00b050 font-weight-bold">Metropolitan Davao Medical Society, Inc</h4>
                                <p class="mb-1 font-weight-bold text-dark">a component society of the Philippine Medical Association</p>
                                <p class="mb-1">Room 210, Fabie General Hospital, Legaspi St.,</p>
                                <p class="mb-1">Davao City, Philippines</p>
                                <p class="mb-1">Tel. No. 3157377 / 09163243985 / 093330453059</p>
                            </div>
                        </div>
                        <div class="col-2 outline-none border-0 bs-0">
                            <img src="/images/layout/logo-mdms.png" class="header-logo outline-none border-0 bs-0" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 border-right-2">
                            <div class="text-center">
                                <h5 class="text-center text-uppercase font-weight-bold text-dark w-50 mt-2 mb-1 officers-label color-131313">
                                    Officers for the year 2022-2023
                                </h5>
                            </div>
                            <div class="cert-officers">
                                <div class="my-3">
                                    <p class="font-weight-bold">Maria Lourdes B. Lacanilao, MD</p>
                                    <p>President</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Gina L. Alvarez, MD</p>
                                    <p>Vice-President</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Maria Theresa G. Bañez, MD</p>
                                    <p>Secretary</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Christina A. Uy, MD</p>
                                    <p>Treasurer</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Roland Victor P. Chiu, MD</p>
                                    <p>Auditor</p>
                                </div>
                            </div>
                            <div class="cert-officers past mt-3">
                                <div class="mt-3">
                                    <p class="font-weight-bold">Medical Cluster Representatives:</p>
                                </div>
                                <div class="mt-2">
                                    <p>Omohaire T. Dilangalen, MD</p>
                                </div>
                                <div class="mt-2">
                                    <p>Cyrus B. Estera, MD</p>
                                </div>
                                <div class="mt-2">
                                    <p>Jose M. Matical III, MD</p>
                                </div>
                                <div class="mt-2">
                                    <p>Loida Michelle R. Ong, MD</p>
                                </div>
                                <div class="mt-2">
                                    <p>Monina G. Pasumbal, MD</p>
                                </div>
                                <div class="mt-2">
                                    <p>Perlita P. Pimentel, MD</p>
                                </div>
                                <div class="mt-2">
                                    <p>Annabelle P. Yumang, MD</p>
                                </div>
                            </div>
                            <div class="cert-officers">
                                <div class="my-3">
                                    <p class="font-weight-bold">Jose G. Beltran Jr., MD</p>
                                    <p>Immediate Past President</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Luz P. Acosta-Barrientos, MD</p>
                                    <p>PMA South Eastern Mindanao Governor</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Maria Minerva P. Calimag, MD</p>
                                    <p>Phil. Medical Association President</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-normal">Specialty Presidents:</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Martha O. Montaño, MD</p>
                                    <p>Philippine Society of Anesthesiologists, Inc.</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Maureen B. De Guzman, MD</p>
                                    <p>Philippine Academy of Family Physicians</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Karen S. Francisco, MD</p>
                                    <p>Philippine Obstetrical and Gynecological Society</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Noemia Bartolome, MD</p>
                                    <p>Philippine Society of Pathology</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Cleofo C. Llanos, MD</p>
                                    <p>Philippine Pediatric Society</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Mark Anthony A. Tom, MD</p>
                                    <p>Philippine College of Physicians</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Maria Theresa T. Sanchez</p>
                                    <p>Philippine College of Radiology</p>
                                </div>
                                <div class="my-3">
                                    <p class="font-weight-bold">Lauralee T. Samonte</p>
                                    <p>Philippine College of Surgeons</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="cert-content-text p-4">
                                <h4 class="text-center">Certification</h4>
                                <p class="text-uppercase font-weight-bold color-131313">To Whom May It Concern:</p>
                                <p>This is to certify that <span class="color-131313">DR. {{ memberName }}, MD </span> has paid her annual dues to Metropolitan Davao Medical Society Inc. and is an active life member of good standing with PMA number of <span class="font-weight-bold color-131313">{{ pma_num }}</span></p>
                                <p>This certification is issued upon the request of <span class="color-131313">Dr. {{ last_name }}</span> for Philippine Health Insurance Accreditation Renewal.</p>
                                <p>Done this <span class="font-weight-bold color-131313">{{ new Date() | dateDay }}</span> day of <span class="font-weight-bold color-131313">{{ new Date() | dateMonth }}</span>  at Davao City, Philippines.</p>
                            </div>
                            <div class="p-4 color-131313 mt-3">
                                <div class="w-100 justify-content-start">
                                    <div class="cert-sign position-relative">
                                        <img src="/images/layout/sign_banes.png" class="sign-1" />
                                        <h5 class="cert-name mb-1 text-uppercase font-weight-bold">Maria Theresa G. Bañes, MD</h5>
                                        <h6>Secretary</h6>
                                    </div>
                                </div>
                                <div class="w-100 justify-content-start mt-5 pt-5">
                                    <div class="cert-sign position-relative">
                                        <img src="/images/layout/sign_uy.png" class="sign-2" />
                                        <h5 class="cert-name mb-1 text-uppercase font-weight-bold">Christina A. Uy, MD</h5>
                                        <h6>Treasurer</h6>
                                    </div>
                                </div>
                                <div class="w-100 justify-content-start mt-5 pt-5">
                                    <div class="cert-sign position-relative">
                                        <img src="/images/layout/sign_lacanilao.png" class="sign-3" />
                                        <h5 class="cert-name mb-1 text-uppercase font-weight-bold">Maria Lourdes B. Lacanilao, MD</h5>
                                        <h6>President</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>    
        </div>

        </el-dialog>        
    </div>
</template>

<script>
import moment from 'moment'
import printHtmlToPDF from "print-html-to-pdf";

export default {
    props: ["showDialog", "memberName", "memberShip", "memberTypes", "tokenValue", "userID", "pcr_num", "pma_num", "last_name", "order"],
    data() {
        return {
            exportText: "Export to PDF",
            is_exporting: false
        }
    },
    filters: {
        dateDay: function (date) {
            return moment(date).format('Do')
        },
        dateMonth: function (date) {
            return moment(date).format('MMMM YYYY')
        },        
    },	    
    methods: {
        getReceiptNo() {
            var date = new Date();
            var day = date.getDate()
            var month = date.getMonth() + 1
            var year = date.getFullYear()
            var receipt = "R-CGS-" + this.userID + "-" + day + month + year
            return receipt
        },
        handleClose() {
            this.$emit('closeCertDialog', false)
        },
        generateReport (){
            this.$refs.html2Pdf.generatePdf()
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
        generateCertificate(tokenValue, order_id) {
            var element = document.getElementById('pdf-cert-box');

            const pdfOption = {
                jsPDF: {
                    unit: 'px',
                    format: 'letter',
                    orientation: "portrait",
                },
                spin: false,
                fileName: 'Certificate',
                pageRanges: 1,
                fitToPage: true
            }

            this.is_exporting = true
            this.exportText = "Exporting"
            printHtmlToPDF.print(element, pdfOption)
            .then(res => {
                this.is_exporting = false
                this.exportText = "Export to PDF"
                this.updateCOGSOrderToClaim(tokenValue, order_id)
			})
			.catch(err => {
				console.error(err)
                this.is_exporting = false
                this.exportText = "Export to PDF"
                this.$message.error("Could not export certificate, please contact the site admin")
			})
        },
        updateCOGSOrderToClaim(tokenValue, order){
            axios.post(this.$store.state.apiUrl + '/api/fees/order/cogs', {'user_id': order.User_ID, 'collection_fee_id': order.id},
            {
                headers: {
                    Authorization: "Bearer " + tokenValue,
                },
            })
            .then(res => {
                this.is_exporting = false
                this.handleClose();
			})
			.catch(err => {
				console.error(err)
                this.is_exporting = false
			})
        }
    },
    components: {
        printHtmlToPDF
    }
}
</script>

<style lang="scss">
.cert-dialog {
    .el-dialog__header {
        padding-bottom: 0;
    }
    .el-dialog__title {
        font-weight: bold;
    }
    .pdf-cert-box {
        border: 1px solid #dddddd;
        overflow-x: scroll;
        overflow-y: hidden;
    }
    .el-dialog__body .vue-html2pdf .layout-container {
        position: inherit!important;
        height: auto!important;
        width: auto!important;
        background: transparent!important;
        z-index: 0!important;        
    }
    .el-dialog__body {
        padding-top: 0px!important;
    }
    .vue-html2pdf .layout-container .row {
        margin: 0;
    }
    .header-logo {
        object-fit: contain;
        width: 100%;
        height: 200px;
    }
    .pdf-header-text .main-title {
        font-weight: bold;
        font-size: 36px;
    }
    .pdf-header-text span {
        font-weight: bold;
        font-size:14px;
    }
    .cert-content-text h4 {
        text-transform: uppercase;
        font-weight: bold;
        color: #131313;
        letter-spacing: 4px;
        margin-bottom: 80px;
    }
    .cert-content-text p {
        font-size: 18px;
        font-weight:400;
        word-break: normal;
        margin-bottom: 30px;
    }
    .cert-content-text p span {
        font-weight: bold;
        /* text-decoration: underline; */
    }
    .cert-barcode .vue-barcode-element {
        width: 100%;
    }
    .cert-officers h5 {
        color: #131313;
    }
    .cert-officers p {
        color: #131313;
        margin: 0;
        font-weight:400;
        font-size: 16px;
    }
    .cert-officers.past p {
        font-weight: normal;
        font-size:14px;
    }
    .cert-sign {
        .sign-1 {
            top: -77px;
            position: absolute;
            height: 135px;
            left: 80px;
        }
        .sign-2 {
            top: -45px;
            position: absolute;
            height: 87px;
            left: 80px;
        }
        .sign-3 {
            top: -45px;
            position: absolute;
            height: 43px;
            left: 43px;
        }
    }
    .receipt {
        font-size: 14px;
        font-weight: bold;
    }
    .border-right-2 {
        border-right: 0.3rem solid black;
    }
    .border-bottom-2 {
        border-bottom: 0.3rem solid black;
    }
    .color-131313 {
        color: #131313;
    }
    .color-00b050 {
        color: #00b050;
    }
    .times-new-roman {
        font-family: 'Times New Roman', Times, serif;
    }
    .officers-label {
        word-break: break-word;
        display: inline-block;
    }
    .outline-none {
        outline: none;
    }
    .bs-0 {
        box-shadow: 0px;
    }
}
</style>