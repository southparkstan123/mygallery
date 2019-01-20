<template>
    <div class="fileUpload">
        <div class="btn btn-primary">
            <span>Select an image</span>
            <input type="file" class="upload" name="thumbnail" accept="image/*" aria-describedby="error-message-file" @change="onThumbnailChange">
        </div>
        <div v-show="previewImage">
            <label for="name">Thumbnail Preview</label>
            <img :src="previewImage" class="img-responsive">
        </div>
        <div v-show="thumbnail">
            <label for="name">Original Thumbnail</label>
            <img :src="thumbnail" class="img-responsive">
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            file: {},
            thumbnail: '',
            previewImage: ''
        }
    },
    methods: {
        //Upload the thumbnail of gallery
        onThumbnailChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },
        //Show the preview thumbnail on the form
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.previewImage = e.target.result;
                vm.file = file;
            };
            reader.readAsDataURL(file);
        },
    }
}
</script>

<style>

</style>
