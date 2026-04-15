<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const authUser = computed(() => page.props.auth?.user ?? null);
const adminMenu = computed(() => page.props.adminMenu ?? []);
const adminStats = computed(() => page.props.adminStats ?? {});
</script>

<template>
    <AppHead title="administrativo" />

    <main class="admin-page">
        <section class="admin-hero">
            <div class="admin-hero__content">
                <div>
                    <p class="admin-eyebrow">Painel administrativo</p>
                    <h1 class="admin-title">Controle geral do sistema</h1>
                    <p class="admin-subtitle">
                        Gerencie o site, acompanhe os acessos principais da estrutura
                        e administre rapidamente as áreas mais importantes do projeto.
                    </p>
                </div>

                <div class="admin-user-card">
                    <span class="admin-user-card__label">Acesso ativo</span>
                    <strong class="admin-user-card__name">
                        {{ authUser?.name || 'Administrador' }}
                    </strong>
                    <span class="admin-user-card__email">
                        {{ authUser?.email || 'Usuário autenticado' }}
                    </span>
                </div>
            </div>
        </section>

        <section class="admin-stats">
            <article class="admin-stat-card">
                <span class="admin-stat-card__label">Links administrativos</span>
                <strong class="admin-stat-card__value">
                    {{ adminStats.total_admin_links ?? 0 }}
                </strong>
            </article>

            <article class="admin-stat-card">
                <span class="admin-stat-card__label">Links públicos</span>
                <strong class="admin-stat-card__value">
                    {{ adminStats.total_public_links ?? 0 }}
                </strong>
            </article>

            <article class="admin-stat-card">
                <span class="admin-stat-card__label">Ações rápidas</span>
                <strong class="admin-stat-card__value">
                    {{ adminStats.total_quick_actions ?? 0 }}
                </strong>
            </article>
        </section>

        <section class="admin-sections" v-if="adminMenu.length">
            <article
                v-for="section in adminMenu"
                :key="section.key"
                class="admin-section-card"
            >
                <div class="admin-section-card__header">
                    <div>
                        <p class="admin-section-card__eyebrow">{{ section.eyebrow }}</p>
                        <h2 class="admin-section-card__title">{{ section.title }}</h2>
                    </div>

                    <span class="admin-section-card__count">
                        {{ section.links.length }} links
                    </span>
                </div>

                <p class="admin-section-card__description">
                    {{ section.description }}
                </p>

                <div class="admin-links-grid">
                    <Link
                        v-for="item in section.links"
                        :key="item.label"
                        :href="item.url"
                        class="admin-link-card"
                    >
                        <span class="admin-link-card__label">{{ item.label }}</span>
                        <span class="admin-link-card__text">{{ item.description }}</span>
                    </Link>
                </div>
            </article>
        </section>

        <section class="admin-actions">
            <Link :href="route('index.home')" class="admin-action admin-action--ghost">
                Ir para a página inicial
            </Link>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                type="button"
                class="admin-action admin-action--danger"
            >
                Sair do painel
            </Link>
        </section>
    </main>
</template>

<style scoped>
.admin-page {
    min-height: 100vh;
    padding: 32px 20px 56px;
    background:
        radial-gradient(circle at top left, rgba(59, 130, 246, 0.14), transparent 30%),
        radial-gradient(circle at top right, rgba(16, 185, 129, 0.10), transparent 28%),
        linear-gradient(180deg, #0b1220 0%, #111827 100%);
    color: #e5eefc;
}

.admin-page,
.admin-page * {
    box-sizing: border-box;
}

.admin-hero {
    max-width: 1280px;
    margin: 0 auto 24px;
}

.admin-hero__content {
    display: grid;
    grid-template-columns: 1.8fr 0.9fr;
    gap: 20px;
    align-items: stretch;
}

.admin-eyebrow {
    margin: 0 0 12px;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #93c5fd;
}

.admin-title {
    margin: 0;
    font-size: clamp(2rem, 4vw, 3rem);
    line-height: 1.05;
    font-weight: 800;
    color: #ffffff;
}

.admin-subtitle {
    margin: 16px 0 0;
    max-width: 780px;
    font-size: 1rem;
    line-height: 1.7;
    color: #c8d5e8;
}

.admin-user-card {
    padding: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.06);
    backdrop-filter: blur(14px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.22);
}

.admin-user-card__label {
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #93c5fd;
}

.admin-user-card__name {
    font-size: 1.25rem;
    color: #ffffff;
}

.admin-user-card__email {
    font-size: 0.95rem;
    color: #bfdbfe;
    word-break: break-word;
}

.admin-stats {
    max-width: 1280px;
    margin: 0 auto 24px;
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.admin-stat-card {
    padding: 22px;
    border-radius: 22px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.18);
}

.admin-stat-card__label {
    display: block;
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: #bfdbfe;
}

.admin-stat-card__value {
    font-size: 2rem;
    font-weight: 800;
    color: #ffffff;
}

.admin-sections {
    max-width: 1280px;
    margin: 0 auto;
    display: grid;
    gap: 22px;
}

.admin-section-card {
    padding: 24px;
    border-radius: 28px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
}

.admin-section-card__header {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 10px;
}

.admin-section-card__eyebrow {
    margin: 0 0 6px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #86efac;
}

.admin-section-card__title {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 800;
    color: #ffffff;
}

.admin-section-card__count {
    white-space: nowrap;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(147, 197, 253, 0.12);
    color: #bfdbfe;
    font-size: 0.85rem;
    font-weight: 700;
}

.admin-section-card__description {
    margin: 0 0 20px;
    line-height: 1.7;
    color: #cbd5e1;
}

.admin-links-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

.admin-link-card {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 18px;
    min-height: 120px;
    text-decoration: none;
    color: inherit;
    border-radius: 20px;
    background: rgba(15, 23, 42, 0.66);
    border: 1px solid rgba(148, 163, 184, 0.18);
    transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
}

.admin-link-card:hover {
    transform: translateY(-3px);
    border-color: rgba(96, 165, 250, 0.55);
    background: rgba(15, 23, 42, 0.9);
}

.admin-link-card__label {
    font-size: 1rem;
    font-weight: 700;
    color: #ffffff;
}

.admin-link-card__text {
    font-size: 0.92rem;
    line-height: 1.6;
    color: #cbd5e1;
}

.admin-actions {
    max-width: 1280px;
    margin: 26px auto 0;
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
}

.admin-action {
    border: 0;
    cursor: pointer;
    text-decoration: none;
    padding: 14px 20px;
    border-radius: 16px;
    font-size: 0.96rem;
    font-weight: 700;
    transition: transform 0.2s ease, opacity 0.2s ease;
}

.admin-action:hover {
    transform: translateY(-2px);
    opacity: 0.95;
}

.admin-action--ghost {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
}

.admin-action--danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
}

@media (max-width: 1080px) {
    .admin-hero__content,
    .admin-stats,
    .admin-links-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .admin-page {
        padding: 20px 14px 42px;
    }

    .admin-section-card,
    .admin-stat-card,
    .admin-user-card {
        padding: 18px;
        border-radius: 20px;
    }

    .admin-section-card__header {
        flex-direction: column;
        align-items: flex-start;
    }

    .admin-actions {
        flex-direction: column;
    }

    .admin-action {
        width: 100%;
        text-align: center;
    }
}
</style>