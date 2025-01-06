import type { Folder } from "@/responses/Folder";
import { folderService } from "@/services/FolderService";
import { useCallback, useEffect, useState } from "react";

export function Offcanvas() {
    const [folder, setFolder] = useState<Folder[]>();

    const getFolders = useCallback(async () => {
        const folders = await folderService.getFolders();
        setFolder(folders);
    }, []);

    useEffect(() => {
        getFolders();
      }, [getFolders]);

    return (
        <>
            <section>
                <i className="fa-solid fa-bars fa-2x" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" />
                <div className="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabIndex={-1} id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                    <div className="offcanvas-header">
                        <button type="button" className="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" />
                    </div>
                    <div className="offcanvas-body">
                        {folder?.map((folder) => (
                            <div key={folder.id}>
                                <a href="/">
                                    <button type="button" className="btn btn-primary w-100">
                                        {folder.name}
                                    </button>
                                </a>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
        </>
    );
}