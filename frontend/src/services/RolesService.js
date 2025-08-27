import http from "@/utils/http.js";

class RolesService {
    static async getData(params) {
        return (await http.get(`/api/roles`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/roles/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/roles`, row)).data;
        } else {
            return (await http.put(`/api/roles/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/roles/${id}`));
    }
}

export default RolesService;
