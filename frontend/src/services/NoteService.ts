import axios from "axios";

class NoteService {
    async getNote(id: number) {
        const res = await axios.get('http://127.0.0.1:8000/api/note/', { params: { id } });
        return res.data;
    }
}

export const noteService = new NoteService();