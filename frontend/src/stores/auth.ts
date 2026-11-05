import { defineStore } from 'pinia'; import api from '../api';
export const useAuthStore=defineStore('auth',{state:()=>({token:localStorage.getItem('legalflow_token')||'',user:null as null|{id:number;name:string;email:string}}),getters:{isAuthenticated:s=>!!s.token},actions:{
 async login(email:string,password:string){const {data}=await api.post('/auth/login',{email,password});this.token=data.token;this.user=data.user;localStorage.setItem('legalflow_token',data.token);},
 async loadUser(){if(!this.token)return;try{const {data}=await api.get('/auth/me');this.user=data.user;}catch{this.logoutLocal();}},
 async logout(){try{await api.post('/auth/logout')}finally{this.logoutLocal()}},
 logoutLocal(){this.token='';this.user=null;localStorage.removeItem('legalflow_token');}
}});
