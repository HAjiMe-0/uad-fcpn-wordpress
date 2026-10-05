(() => {
	'use strict';

	const body = document.body;
	const menuToggle = document.querySelector('[data-menu-toggle]');
	const siteNav = document.querySelector('[data-site-nav]');

	const closeMenu = () => {
		if (!menuToggle || !siteNav) return;
		menuToggle.setAttribute('aria-expanded', 'false');
		siteNav.classList.remove('is-open');
		body.classList.remove('menu-open');
	};

	if (menuToggle && siteNav) {
		menuToggle.addEventListener('click', () => {
			const open = menuToggle.getAttribute('aria-expanded') === 'true';
			menuToggle.setAttribute('aria-expanded', String(!open));
			siteNav.classList.toggle('is-open', !open);
			body.classList.toggle('menu-open', !open);
		});

		siteNav.addEventListener('click', (event) => {
			if (event.target.closest('a')) closeMenu();
		});

		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') closeMenu();
		});

		window.addEventListener('resize', () => {
			if (window.innerWidth > 820) closeMenu();
		});
	}

	const form = document.querySelector('[data-document-search]');
	const results = document.querySelector('[data-document-results]');
	const pagination = document.querySelector('[data-document-pagination]');
	const resultCount = document.querySelector('[data-results-count]');
	const status = document.querySelector('[data-search-status]');

	if (!form || !results || !pagination || typeof window.uadDocuments === 'undefined') return;

	const queryInput = form.querySelector('[name="buscar_documento"]');
	const categoryInput = form.querySelector('[name="categoria_documento"]');
	const yearInput = form.querySelector('[name="gestion_documento"]');
	let debounceTimer;
	let controller;

	const currentFilters = () => ({
		query: queryInput ? queryInput.value.trim() : '',
		category: categoryInput ? categoryInput.value : '',
		year: yearInput ? yearInput.value : '',
	});

	const updateUrl = (filters, page) => {
		const url = new URL(window.location.href);
		const values = {
			buscar_documento: filters.query,
			categoria_documento: filters.category,
			gestion_documento: filters.year,
			pagina_documentos: page > 1 ? String(page) : '',
		};

		Object.entries(values).forEach(([key, value]) => {
			if (value) url.searchParams.set(key, value);
			else url.searchParams.delete(key);
		});
		url.hash = 'documentos';
		window.history.replaceState({}, '', url.toString());
	};

	const searchDocuments = async (page = 1, changeUrl = true) => {
		if (controller) controller.abort();
		controller = new AbortController();
		const filters = currentFilters();
		const payload = new FormData();
		payload.append('action', 'uad_search_documents');
		payload.append('nonce', window.uadDocuments.nonce);
		payload.append('query', filters.query);
		payload.append('category', filters.category);
		payload.append('year', filters.year);
		payload.append('page', String(page));

		results.classList.add('is-loading');
		results.setAttribute('aria-busy', 'true');
		if (status) status.textContent = window.uadDocuments.labels.loading;

		try {
			const response = await fetch(window.uadDocuments.ajaxUrl, {
				method: 'POST',
				body: payload,
				credentials: 'same-origin',
				signal: controller.signal,
			});

			if (!response.ok) throw new Error(`HTTP ${response.status}`);
			const json = await response.json();
			if (!json.success || !json.data) throw new Error('Invalid response');

			results.innerHTML = json.data.html;
			pagination.innerHTML = json.data.pagination;
			if (resultCount) resultCount.textContent = json.data.label;
			if (status) status.textContent = '';
			if (changeUrl) updateUrl(filters, page);
		} catch (error) {
			if (error.name !== 'AbortError' && status) {
				status.textContent = window.uadDocuments.labels.error;
			}
		} finally {
			results.classList.remove('is-loading');
			results.removeAttribute('aria-busy');
		}
	};

	form.addEventListener('submit', (event) => {
		event.preventDefault();
		searchDocuments(1);
	});

	if (queryInput) {
		queryInput.addEventListener('input', () => {
			window.clearTimeout(debounceTimer);
			debounceTimer = window.setTimeout(() => searchDocuments(1), 350);
		});
	}

	[categoryInput, yearInput].filter(Boolean).forEach((field) => {
		field.addEventListener('change', () => searchDocuments(1));
	});

	pagination.addEventListener('click', (event) => {
		const link = event.target.closest('[data-page]');
		if (!link) return;
		event.preventDefault();
		const page = Number.parseInt(link.dataset.page, 10) || 1;
		searchDocuments(page);
		document.querySelector('#documentos')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
	});
})();

