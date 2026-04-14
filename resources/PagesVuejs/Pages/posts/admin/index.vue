<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const posts = page.props.posts;
</script>

<template>
  <main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3 m-0">Admin • Posts</h1>
      <Link :href="route('admin.posts.create')" class="btn btn-primary">Novo post</Link>
    </div>

    <div class="table-responsive bg-white rounded border">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>Título</th>
            <th>Status</th>
            <th>Categoria</th>
            <th>Atualizado em</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in posts.data" :key="post.id">
            <td>{{ post.title }}</td>
            <td>
              <span class="badge" :class="post.is_published ? 'text-bg-success' : 'text-bg-secondary'">
                {{ post.status }}
              </span>
            </td>
            <td>{{ post.category || '—' }}</td>
            <td>{{ post.updated_at || '—' }}</td>
            <td class="d-flex gap-2">
              <Link :href="route('admin.posts.edit', post.slug)" class="btn btn-sm btn-outline-primary">Editar</Link>
              <a :href="post.is_published ? route('posts.show', post.slug) : post.preview_url" target="_blank" class="btn btn-sm btn-outline-dark">{{ post.is_published ? 'Ver público' : 'Preview draft' }}</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>
