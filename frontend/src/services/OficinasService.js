import http from "@/utils/http.js";

class OficinasService {
    static async getData(params) {
        return (await http.get(`/api/oficinas`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/oficinas/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/oficinas`, row)).data;
        } else {
            return (await http.put(`/api/oficinas/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/oficinas/${id}`));
    }
}

export default OficinasService;
