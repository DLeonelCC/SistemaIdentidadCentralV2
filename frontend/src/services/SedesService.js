import http from "@/utils/http.js";

class SedesService {
    static async getData(params) {
        return (await http.get(`/api/sedes`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/sedes/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/sedes`, row)).data;
        } else {
            return (await http.put(`/api/sedes/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/sedes/${id}`));
    }
}

export default SedesService;
