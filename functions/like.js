// Importa las funciones necesarias para inicializar Firebase y usar Firestore
import { initializeApp } from "firebase/app";
import { getFirestore, doc, getDoc, setDoc, updateDoc, increment } from "firebase/firestore";

// Configuración de Firebase
const firebaseConfig = {
  apiKey: "AIzaSyCk_tGfeKTillU4GwJ3fLOl-v_MzLTHizs",
  authDomain: "scholary-5bd45.firebaseapp.com",
  projectId: "scholary-5bd45",
  storageBucket: "scholary-5bd45.appspot.com",
  messagingSenderId: "565098748615",
  appId: "1:565098748615:web:5b9f679f0442ebbb657ce2",
  measurementId: "G-7DFCBBNB7E" // Este campo se puede omitir si no usas Analytics
};

// Inicializa Firebase y Firestore
const app = initializeApp(firebaseConfig);
const db = getFirestore(app); // Esto permite interactuar con Firestore

// Funciones para gestionar los "likes" (como en el código anterior)
async function getLikesFromFirestore(id) {
    const docRef = doc(db, "likes", id.toString());
    const docSnap = await getDoc(docRef);
    return docSnap.exists() ? docSnap.data().count : 0;
}

async function updateLikesInFirestore(id) {
    const docRef = doc(db, "likes", id.toString());
    const docSnap = await getDoc(docRef);

    if (docSnap.exists()) {
        await updateDoc(docRef, { count: increment(1) });
    } else {
        await setDoc(docRef, { count: 1 });
    }

    localStorage.setItem(`liked_${id}`, 'true');
    return getLikesFromFirestore(id);
}

function hasLiked(id) {
    return localStorage.getItem(`liked_${id}`) === 'true';
}

async function handleLike(id) {
    if (hasLiked(id)) {
        alert("Ya has dado like a esta noticia.");
        return;
    }

    const newCount = await updateLikesInFirestore(id);
    document.getElementById(`like-count-${id}`).innerText = `Likes: ${newCount}`;
}
