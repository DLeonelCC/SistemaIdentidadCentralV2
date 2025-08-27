import http from "@/utils/http.js";

class ProyectosService {
    static async getData(params) {
        return (await http.get(`/api/proyectos`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/proyectos/${id}`)).data;
    }

    static async save(row) {
        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/proyectos`, row)).data;
        } else {
            return (await http.put(`/api/proyectos/${row.id}`, row)).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/proyectos/${id}`));
    }
}

export default ProyectosService;
