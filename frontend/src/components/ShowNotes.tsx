import type { Folder } from "@/responses/Folder";
import type { Note } from "@/responses/Note";
import { noteService } from "@/services/NoteService";
import { useCallback, useEffect, useState } from "react";

export function ShowNotes ({folders}: {folders: Folder[]}) {
    const [notes, setNotes] = useState<Note[]>();

    const getNotes = useCallback(async () => {
            folders.map((folder) => {
                folder.notesIds.map((noteId) => {
                    noteService.getNote(noteId).then((note) => {
                        setNotes(note);
                    });
                })
            })
        }, [folders]);

    useEffect(() => {
        getNotes();
    }, [getNotes]);

    return (
        <>
            <div>
                <p>
                    {notes?.map((note) => (
                        <div key={note.id}>
                            <p>{note.title}</p>
                            <p>{note.content}</p>
                            <p>{note.creationDate}</p>
                            <p>{note.tag}</p>
                            <p>{note.folderId}</p>
                        </div>
                    ))}
                </p>
            </div>
        </>
    );
}