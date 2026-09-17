import { useForm } from '@inertiajs/react';
import { useState } from 'react';
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export default function DeleteUserForm() {
    const [open, setOpen] = useState(false);
    const { data, setData, delete: destroy, processing, errors, reset } = useForm({ password: '' });

    function confirmDelete(e: React.FormEvent) {
        e.preventDefault();
        destroy('/profile', {
            onSuccess: () => setOpen(false),
        });
    }

    return (
        <div>
            <p className="mb-4 max-w-md text-sm text-muted-foreground">
                Một khi tài khoản bị xoá, toàn bộ dữ liệu sẽ bị xoá vĩnh viễn. Vui lòng tải xuống dữ liệu bạn muốn
                giữ lại trước khi tiếp tục.
            </p>
            <AlertDialog
                open={open}
                onOpenChange={(next) => {
                    if (next) reset();
                    setOpen(next);
                }}
            >
                <AlertDialogTrigger asChild>
                    <Button variant="destructive">Xoá tài khoản</Button>
                </AlertDialogTrigger>
                <AlertDialogContent>
                    <form onSubmit={confirmDelete}>
                        <AlertDialogHeader>
                            <AlertDialogTitle>Xoá tài khoản</AlertDialogTitle>
                            <AlertDialogDescription>Nhập mật khẩu để xác nhận.</AlertDialogDescription>
                        </AlertDialogHeader>
                        <div className="my-4 flex flex-col gap-1.5">
                            <Label htmlFor="delete-password">Mật khẩu</Label>
                            <Input
                                id="delete-password"
                                type="password"
                                autoFocus
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
                        </div>
                        <AlertDialogFooter>
                            <AlertDialogCancel type="button">Huỷ</AlertDialogCancel>
                            <Button type="submit" variant="destructive" disabled={processing}>
                                Xác nhận xoá
                            </Button>
                        </AlertDialogFooter>
                    </form>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    );
}
