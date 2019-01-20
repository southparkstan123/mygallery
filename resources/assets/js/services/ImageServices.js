import axios from 'axios';

export default class ImageServices {
    static async updateImage(imageObj){
        try {
            let formData = new FormData();

            formData.append('published',imageObj.published);
            formData.append('description',imageObj.description);
            formData.append('gallery_id',imageObj.gallery_id);

            return await axios.post('/api/v1/images/update/' + imageObj.id, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
        } catch (error) {
            return error
        }
    }

    static async deleteImage(imageID){
        try {
            return await axios.delete('/api/v1/images/delete/' + imageID);
        } catch (error) {
            return error;
        }
    }

    static async createImages(file, galleryId){
        try {
            let formData = new FormData();
            formData.append('File',file);
            formData.append('gallery_id', galleryId);

            return await axios.post('/api/v1/images/create', formData, {
                headers: { 
                    'Content-Type': 'multipart/form-data' , 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
        } catch (error) {
            return error;
        }

    }

    static async publishAllImages(galleryId){
        let formData = new FormData();
        formData.append('gallery_id',galleryId);

        try {
            return await axios.post('/api/v1/images/publishAll/', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
        } catch (error) {
            return error
        }
    }
}