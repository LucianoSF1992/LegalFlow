<script setup lang="ts">
import {computed} from 'vue';import {useRoute,useRouter} from 'vue-router';import {useAuthStore} from './stores/auth';
const auth=useAuthStore(),route=useRoute(),router=useRouter();const showNav=computed(()=>route.path!='/login');
async function signOut(){await auth.logout();router.push('/login')}
</script>
<template><div class="app-shell"><aside v-if="showNav" class="sidebar"><div class="brand"><span class="brand-mark">L</span><div><b>LegalFlow</b><small>GESTÃO JURÍDICA</small></div></div><nav><RouterLink to="/">Visão geral</RouterLink><RouterLink to="/clients">Clientes</RouterLink><RouterLink to="/cases">Processos</RouterLink><RouterLink to="/tasks">Tarefas</RouterLink><RouterLink to="/documents">Documentos</RouterLink></nav><div class="sidebar-bottom"><span>{{auth.user?.name||'Usuário'}}</span><button class="link-button" @click="signOut">Sair da conta ↗</button></div></aside><main class="main-area"><header v-if="showNav" class="topbar"><span>Workspace / {{route.path==='/'?'Visão geral':route.path.slice(1)}}</span><span class="user-pill">{{auth.user?.name||'LegalFlow'}}</span></header><RouterView/></main></div></template>
