const BASE = "/wp-json/ai-zippy/v1";

export async function fetchProducts(params = {}) {
	const query = new URLSearchParams();

	Object.entries(params).forEach(([key, value]) => {
		if (value !== "" && value !== null && value !== undefined) {
			query.set(key, value);
		}
	});

	const res = await fetch(`${BASE}/products?${query.toString()}`);
	if (!res.ok) throw new Error("Failed to fetch products");
	return res.json();
}

export async function fetchFilterOptions(params = {}) {
	const query = new URLSearchParams();

	["category", "exclude_category"].forEach((key) => {
		const value = params[key];
		if (value !== "" && value !== null && value !== undefined) {
			query.set(key, value);
		}
	});

	const suffix = query.toString() ? `?${query.toString()}` : "";
	const res = await fetch(`${BASE}/filter-options${suffix}`);
	if (!res.ok) throw new Error("Failed to fetch filter options");
	return res.json();
}
