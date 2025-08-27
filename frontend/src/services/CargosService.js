import http from "@/utils/http.js";

class CargosService {
    static async getData(params) {
        return (await http.get(`/api/cargos`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/cargos/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/cargos`, row)).data;
        } else {
            return (await http.put(`/api/cargos/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/cargos/${id}`));
    }
}

export default CargosService;
