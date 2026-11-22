import {
    BadgeCheck, Box, Image as ImageIcon, Instagram, LayoutDashboard,
    Monitor, Palette, PenTool, ScanEye, Sparkles, Type, WandSparkles,
} from 'lucide-vue-next';

const icons = {
    'badge-check': BadgeCheck,
    box: Box,
    image: ImageIcon,
    instagram: Instagram,
    'layout-dashboard': LayoutDashboard,
    monitor: Monitor,
    palette: Palette,
    'pen-tool': PenTool,
    'scan-eye': ScanEye,
    sparkles: Sparkles,
    type: Type,
    'wand-sparkles': WandSparkles,
};

export function iconFor(name) {
    return icons[name] || Sparkles;
}
