import axios from 'axios';

export default class GalleryServices {

    static async createGallery(gallery){
        try {
            let formData = new FormData();
            formData.append('name',gallery.name);
            formData.append('published',gallery.published ? '1' : '0');
            formData.append('description',gallery.description);

            const response = await axios.post('/api/v1/gallery/create/', formData, {             
                headers: { 
                    'Content-Type': 'multipart/form-data' 
                }         
            });
            
            return response;
        } catch (error) {
            return error
        }
    }

    static async updateGallery(gallery){
        try {
            let formData = new FormData();
            formData.append('published', gallery.published ? '1' : '0');
            formData.append('description', gallery.description);
            formData.append('name', gallery.name);
    
            return await axios.post('/api/v1/gallery/update/' + gallery.id, formData, {             
                headers: { 
                    'Content-Type': 'multipart/form-data' 
                }         
            })
    
        } catch (error) {
            return error;
        }
    }

    static async getGallery(id){
        try {
            return await axios.get('/api/v1/gallery/' + id)
        } catch (error) {
            return error;
        }
    }

    static async deleteGallery(id){
        try {
            return await axios.delete('/api/v1/gallery/delete/' + id)
        } catch (error) {
            return error;
        }
    }

    static async getGalleries(){
        try {
            return await axios.get('/api/v1/gallery', {            
                headers: { 
                    'Content-Type': 'application/json' ,
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            });
        } catch (error) {
            return error;
        }
    }

    static async getGalleryDorpdownList(){
        try {
            return await axios.get('/api/v1/list_gallery')
        } catch (error) {
            return error;
        }
    }

}