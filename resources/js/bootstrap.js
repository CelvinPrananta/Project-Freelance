import 'bootstrap';
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.interceptors.response.use(function(response) {
    if(!response.headers["content-type"]) return response;
    const ctype = response.headers["content-type"];
    if(ctype !== "text/html; charset=UTF-8") return response;
    const responseUrl = response?.request?.responseURL;
    if(!responseUrl) return response;
    const locationMatch = (location.href === response?.request?.responseURL);
    if(locationMatch) return response;
    window.location = responseUrl;
}, function(error) {
    return Promise.reject(error);
});