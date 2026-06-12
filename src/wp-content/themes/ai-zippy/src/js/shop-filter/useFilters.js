import { useState, useCallback, useRef, useEffect, useMemo } from "react";

const DEFAULTS = {
	search: "",
	category: "",
	exclude_category: "",
	min_price: 0,
	max_price: 0,
	attributes: "",
	stock_status: "",
	orderby: "menu_order",
	order: "ASC",
	page: 1,
	per_page: 12,
};

export default function useFilters(config = {}) {
	const lockedCategoryConfig = config.locked_category || "";
	const categoryConfig = config.category || "";
	const excludedCategoryConfig = config.exclude_category || "";
	const perPageConfig = config.per_page || DEFAULTS.per_page;
	const lockedCategories = useMemo(
		() => normalizeCategoryList(lockedCategoryConfig),
		[lockedCategoryConfig],
	);
	const excludedCategories = useMemo(
		() => normalizeCategoryList(excludedCategoryConfig),
		[excludedCategoryConfig],
	);
	const baseFilters = useMemo(
		() => ({
			...DEFAULTS,
			per_page: perPageConfig,
			category: mergeCategories(categoryConfig, lockedCategories),
			exclude_category: excludedCategoryConfig,
		}),
		[categoryConfig, excludedCategoryConfig, lockedCategories, perPageConfig],
	);

	const [filters, setFilters] = useState(() => {
		// Read initial state from URL params, with config overrides
		const params = new URLSearchParams(window.location.search);
		const initial = { ...baseFilters };

		for (const key of Object.keys(DEFAULTS)) {
			const val = params.get(key);
			if (val !== null) {
				initial[key] = typeof DEFAULTS[key] === "number" ? Number(val) : val;
			}
		}

		initial.category = mergeCategories(initial.category, lockedCategories);

		return initial;
	});

	const timeoutRef = useRef(null);

	const updateFilter = useCallback((key, value) => {
		setFilters((prev) => ({
			...prev,
			[key]: key === "category" ? mergeCategories(value, lockedCategories) : value,
			page: key === "page" ? value : 1, // Reset page when filter changes
		}));
	}, [lockedCategories]);

	const updateMultiple = useCallback((updates) => {
		setFilters((prev) => ({
			...prev,
			...updates,
			category: mergeCategories(
				updates.category ?? prev.category,
				lockedCategories,
			),
			page: updates.page ?? 1,
		}));
	}, [lockedCategories]);

	const resetFilters = useCallback(() => {
		setFilters({ ...baseFilters });
	}, [baseFilters]);

	const setSearch = useCallback(
		(value) => {
			// Debounce search
			clearTimeout(timeoutRef.current);
			timeoutRef.current = setTimeout(() => {
				updateFilter("search", value);
			}, 300);
		},
		[updateFilter],
	);

	// Sync filters to URL
	useEffect(() => {
		const params = new URLSearchParams();
		for (const [key, value] of Object.entries(filters)) {
			if (value !== baseFilters[key] && value !== "" && value !== 0) {
				params.set(key, value);
			}
		}
		const qs = params.toString();
		const url = window.location.pathname + (qs ? `?${qs}` : "");
		window.history.replaceState(null, "", url);
	}, [baseFilters, filters]);

	// Build attributes string from object: { pa_color: ['red'], pa_size: ['l'] } -> "pa_color:red|pa_size:l"
	const toggleAttribute = useCallback(
		(taxonomy, termSlug) => {
			setFilters((prev) => {
				const current = prev.attributes ? parseAttributes(prev.attributes) : {};

				if (!current[taxonomy]) {
					current[taxonomy] = [];
				}

				const idx = current[taxonomy].indexOf(termSlug);
				if (idx === -1) {
					current[taxonomy].push(termSlug);
				} else {
					current[taxonomy].splice(idx, 1);
				}

				if (current[taxonomy].length === 0) {
					delete current[taxonomy];
				}

				return {
					...prev,
					attributes: serializeAttributes(current),
					page: 1,
				};
			});
		},
		[],
	);

	const toggleCategory = useCallback(
		(slug) => {
			if (lockedCategories.includes(slug)) {
				return;
			}

			setFilters((prev) => {
				const current = prev.category ? prev.category.split(",") : [];
				const idx = current.indexOf(slug);

				if (idx === -1) {
					current.push(slug);
				} else {
					current.splice(idx, 1);
				}

				return {
					...prev,
					category: mergeCategories(current.join(","), lockedCategories),
					page: 1,
				};
			});
		},
		[lockedCategories],
	);

	return {
		filters,
		updateFilter,
		updateMultiple,
		resetFilters,
		setSearch,
		toggleAttribute,
		toggleCategory,
		lockedCategories,
		excludedCategories,
	};
}

function normalizeCategoryList(category) {
	return category
		.split(",")
		.map((slug) => slug.trim())
		.filter(Boolean);
}

function mergeCategories(category, lockedCategories) {
	const selected = normalizeCategoryList(category);
	return Array.from(new Set([...selected, ...lockedCategories])).join(",");
}

function parseAttributes(str) {
	const result = {};
	if (!str) return result;

	str.split("|").forEach((group) => {
		const [taxonomy, terms] = group.split(":");
		if (taxonomy && terms) {
			result[taxonomy] = terms.split(",");
		}
	});
	return result;
}

function serializeAttributes(obj) {
	return Object.entries(obj)
		.filter(([, terms]) => terms.length > 0)
		.map(([taxonomy, terms]) => `${taxonomy}:${terms.join(",")}`)
		.join("|");
}
