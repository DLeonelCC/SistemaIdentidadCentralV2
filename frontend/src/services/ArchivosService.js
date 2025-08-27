import http from "@/utils/http.js";

class ArchivosService {
    static async getData(params) {
        return (await http.get(`/api/archivos`, params)).data;
    }

    static async get(id) {
        return (await http.get(`/api/archivos/${id}`)).data;
    }

    static async save(row) {
        var formData = new FormData();

        //ARCHIVO
        if (row.tipo != null) {
            formData.append(`tipo`, row.tipo);
        }
        if (row.tipo_doc != null) {
            formData.append(`tipo_doc`, row.tipo_doc);
        }
        if (row.num_doc != null) {
            formData.append(`num_doc`, row.num_doc);
        }
        if (row.fecha != null) {
            formData.append(`fecha`, row.fecha);
        }
        if (row.descripcion != null) {
            formData.append(`descripcion`, row.descripcion);
        }
        if (row.file != null) {
            formData.append(`file`, row.file);
        }
        if (row.contrato_id != null) {
            formData.append(`contrato_id`, row.contrato_id);
        }

        if (row.id === undefined || row.id === null) {
            return (await http.post(`/api/archivos`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            })).data;
        } else {
            return (await http.post(`/api/archivos-update`, formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            })).data;
        }
    }

    static async delete(id) {
        return (await http.delete(`/api/archivos/${id}`));
    }
}

export default ArchivosService;