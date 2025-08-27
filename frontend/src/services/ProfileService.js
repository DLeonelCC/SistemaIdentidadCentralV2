import http from "@/utils/http.js";

class ProfileService {
    static async getMyInformation() {
        return (await http.get(`/api/get-my-information`)).data;
    }

    static async updateProfileInformation(row) {
        var formData = new FormData();
        formData.append(`onlyPhoto`, row.onlyPhoto);
        formData.append(`id`, row.id);
        formData.append(`name`, row.name);
        formData.append(`email`, row.email);
        formData.append(`photo`, row.photo);
        return (await http.post(`/api/update-profile-information`, formData, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        })).data;
    };

    static async updatePersonaInformation(row) {
        return (await http.post(`/api/update-persona-information`, row)).data;
    }

    static async updatePassword(row) {
        return (await http.post(`/api/update-password`, row)).data;
    };

    static async deleteProfilePhoto(params) {
        return (await http.get(`/api/delete-profile-photo`, params)).data;
    };
}

export default ProfileService;