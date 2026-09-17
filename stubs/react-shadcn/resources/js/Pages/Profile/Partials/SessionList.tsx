import { router } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

interface Session {
    id: string;
    is_current_device: boolean;
    ip_address: string | null;
    user_agent: string | null;
    last_active: string;
}

export default function SessionList({ sessions }: { sessions: Session[] }) {
    function revoke(id: string) {
        router.delete(`/sessions/${id}`);
    }

    function revokeOthers(e: React.FormEvent) {
        e.preventDefault();
        router.delete('/sessions/other');
    }

    return (
        <div>
            <ul className="divide-y">
                {sessions.map((session) => (
                    <li key={session.id} className="flex items-center justify-between gap-4 py-3 text-sm">
                        <div>
                            <p className="font-medium">
                                {session.ip_address}
                                {session.is_current_device && (
                                    <Badge variant="success" className="ml-2">
                                        thiết bị này
                                    </Badge>
                                )}
                            </p>
                            <p className="text-muted-foreground">
                                {session.user_agent} · {session.last_active}
                            </p>
                        </div>
                        {!session.is_current_device && (
                            <Button variant="ghost" size="sm" onClick={() => revoke(session.id)}>
                                Đăng xuất
                            </Button>
                        )}
                    </li>
                ))}
            </ul>
            <form onSubmit={revokeOthers} className="mt-4">
                <Button type="submit" variant="secondary" size="sm">
                    Đăng xuất tất cả thiết bị khác
                </Button>
            </form>
        </div>
    );
}
