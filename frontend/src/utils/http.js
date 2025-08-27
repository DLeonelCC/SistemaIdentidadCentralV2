import axios from "axios";
import Cookies from "js-cookie";

var sessionToken = Cookies.get("authToken");

// Crear instancia de Axios
const http = axios.create({
    baseURL: import.meta.env.VITE_APP_API_URL,
    headers: {
        Authorization: sessionToken
    },
    withCredentials: true,
});

// Interceptor para establecer el token dinámicamente en cada request
// http.interceptors.request.use((config) => {
//     const token = Cookies.get("authToken");
//     if (token) {
//         config.headers.Authorization = token;
//     }
//     return config;
// });

export default http;
