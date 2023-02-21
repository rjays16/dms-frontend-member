<template>
	<div class="ideapay-video">
        <el-dialog :visible.sync="showDialog" :before-close="handleClose" width="75%" >
            <div class="iframe-item">
                <iframe src="https://player.vimeo.com/video/736737801?h=cf5d21755f" width="448" height="252" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            </div>
        </el-dialog>
	</div>
</template>

<script>
export default {
    props: ["showDialog" ],
    data() {
        return {
        }
    }, 
    methods: {
        handleClose() {
            this.$emit('closeIdeapayDialog', false)
        },
	}
}
</script>
<style lang="scss" scoped>
.iframe-item {
    position: relative;
    padding-top: 1%;
    padding-bottom: 56.25%;
    height: 0;
    width: 100%;
    overflow: hidden;
}
.iframe-item iframe {
    position: absolute;
    width: 100%;
    height: 100%;
}
</style>