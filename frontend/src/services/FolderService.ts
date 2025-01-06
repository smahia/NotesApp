import axios from "axios";

class FolderService {
    async getFolders() {
        /* const res = await fetch('http://localhost:8000/api/folder');
        return res.json(); */
        const res = await axios.get('http://localhost:8000/api/folder');
        return res.data;

    }
}

export const folderService = new FolderService();