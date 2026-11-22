export function resolveTitle(props = {}) {
    return props.title || (props.site?.profile?.name ? `${props.site.profile.name} — Creative Portfolio` : 'Premium Portfolio');
}

export function resolveDescription(props = {}) {
    return props.description || props.site?.settings?.meta_description || 'Premium digital creative portfolio — design, branding and digital experiences.';
}
