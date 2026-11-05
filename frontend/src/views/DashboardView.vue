
<script setup lang="ts">
import { onMounted, ref } from 'vue';
import api from '../api';

const data = ref<any>(null);
const error = ref('');

function formatDate(value: string | null) {
  if (!value) return 'Sem prazo';

  const date = value.substring(0, 10);
  const [year, month, day] = date.split('-');

  return `${day}/${month}/${year}`;
}

onMounted(async () => {
  try {
    data.value = (await api.get('/dashboard')).data;
  } catch {
    error.value = 'Não foi possível carregar os indicadores.';
  }
});
</script>

<template>
  <section class="page">
    <div class="page-heading">
      <div>
        <p class="eyebrow">PAINEL DE CONTROLE</p>
        <h1>Visão geral</h1>
        <p class="muted">Acompanhe a operação do seu escritório.</p>
      </div>
      <span class="date-chip">● Ambiente demonstrativo</span>
    </div>

    <p v-if="error" class="error">{{ error }}</p>

    <div v-if="data" class="stats-grid">
      <article class="stat-card">
        <span>Clientes cadastrados</span>
        <strong>{{ data.clients }}</strong>
        <small>Total na carteira</small>
      </article>

      <article class="stat-card">
        <span>Processos ativos</span>
        <strong>{{ data.active_cases }}</strong>
        <small>Em acompanhamento</small>
      </article>

      <article class="stat-card">
        <span>Tarefas em aberto</span>
        <strong>{{ data.open_tasks }}</strong>
        <small>Exigem acompanhamento</small>
      </article>

      <article class="stat-card">
        <span>Tarefas atrasadas</span>
        <strong>{{ data.overdue_tasks }}</strong>
        <small>Verifique os prazos</small>
      </article>
    </div>

    <div class="content-grid">
      <article class="panel">
        <div class="panel-heading">
          <h2>Processos recentes</h2>
          <RouterLink to="/cases">Ver todos</RouterLink>
        </div>

        <div v-if="data?.recent_cases?.length" class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Processo</th>
                <th>Cliente</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in data.recent_cases" :key="c.id">
                <td>
                  <b>{{ c.title }}</b>
                  <small>{{ c.case_number }}</small>
                </td>
                <td>{{ c.client?.name }}</td>
                <td>
                  <span class="status" :class="c.status">{{ c.status }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <p v-else class="empty">Nenhum processo cadastrado.</p>
      </article>

      <article class="panel">
        <div class="panel-heading">
          <h2>Próximas tarefas</h2>
          <RouterLink to="/tasks">Ver todas</RouterLink>
        </div>

        <div v-if="data?.upcoming_tasks?.length" class="task-list">
          <div v-for="t in data.upcoming_tasks" :key="t.id" class="task-row">
            <span class="task-dot"></span>
            <div>
              <b>{{ t.title }}</b>
              <small>Prazo: {{ formatDate(t.due_date) }}</small>
            </div>
          </div>
        </div>

        <p v-else class="empty">Nenhuma tarefa próxima.</p>
      </article>
    </div>

    <p class="footnote">
      LegalFlow é um projeto demonstrativo. Utilize somente dados fictícios.
    </p>
  </section>
</template>
