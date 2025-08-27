import http from "@/utils/http.js";

class PersonasService {
    static async getData(params) {
        return (await http.get(`/api/personas`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/personas/${id}`)).data;
    }

    static async getByDni(num_doc) {
        return (await http.get(`/api/personas/persona-by-dni/${num_doc}`)).data;
    }

    static async searchByApiDni(num_doc) {
        return (await http.get(`/api/personas/persona-by-api-dni/${num_doc}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/personas`, row)).data;
        } else {
            return (await http.put(`/api/personas/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/personas/${id}`));
    }
}

export default PersonasService;
