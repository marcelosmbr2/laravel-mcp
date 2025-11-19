import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => (
    <div className="flex min-h-screen bg-zinc-50 font-sans dark:bg-black">
        <div className="container mx-auto p-4">
            <header className="w-full flex justify-between items-center">
                <h1 className="text-2xl font-bold mb-4">Laravel MCP</h1>
            </header>
            <main>
                {children}
            </main>
        </div>
    </div>
);
