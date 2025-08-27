import http from "@/utils/http.js";

class PersonalsService {
    static async getData(params) {
        return (await http.get(`/api/personals`, params)).data;
    }

    static async get(id, params) {
        return (await http.get(`/api/personals/${id}`, params)).data;
    }

    static async getByDni(num_doc) {
        return (await http.get(`/api/personals/personal-by-dni/${num_doc}`)).data;
    }
    
    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/personals`, row)).data;
        } else {
            return (await http.put(`/api/personals/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/personals/${id}`));
    }
}

export default PersonalsService;
