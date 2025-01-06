import { useCallback, useEffect, useState } from 'react';
import './App.css';
import { Footer } from '@/components/Footer';
import { Offcanvas } from '@/components/Offcanvas';
import { ShowNotes } from '@/components/ShowNotes';
import type { Folder } from '@/responses/Folder';
import { folderService } from '@/services/FolderService';

function App() {
  // TODO: change this to another place, use context?
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
      <main className='p-2'>
        <section>
          <Offcanvas />
        </section>
        {folder && <ShowNotes folders={folder} />}
      </main>
      <Footer />
    </>
  );
}

export default App;
