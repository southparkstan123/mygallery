import axios from 'axios';

export default class WeatherServices{
    static async getWeatherInformation(location) {
        try {
            let formData = new FormData();
            formData.append('location', location);

            return await axios.post('api/v1/getWeather/', formData)
        } catch (error) {
            return error
        }
    }
}