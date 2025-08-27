import http from "@/utils/http.js";

class PermissionsService {
    static async getData(params) {
        return (await http.get(`/api/permisos`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/permisos/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/permisos`, row)).data;
        } else {
            return (await http.put(`/api/permisos/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/permisos/${id}`));
    }
}

export default PermissionsService;
