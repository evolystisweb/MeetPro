import { useState } from 'react';
import { useAdminData } from '@/hooks/useAdminData';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Badge } from '../ui/badge';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '../ui/table';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '../ui/select';
import { Search, UserCheck, UserX, Shield, User } from 'lucide-react';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '../ui/alert-dialog';

export function UsersManagement() {
  const { users, updateUser, refetchUsers } = useAdminData();
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedUser, setSelectedUser] = useState<any>(null);
  const [actionDialogOpen, setActionDialogOpen] = useState(false);
  const [actionType, setActionType] = useState<'activate' | 'deactivate' | 'role'>('activate');

  const filteredUsers = users.filter(user =>
    user.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
    user.first_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    user.last_name.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleAction = async () => {
    if (!selectedUser) return;

    if (actionType === 'activate' || actionType === 'deactivate') {
      await updateUser(selectedUser.id, { is_active: actionType === 'activate' });
    }

    setActionDialogOpen(false);
    setSelectedUser(null);
  };

  const handleRoleChange = async (userId: string, newRole: string) => {
    await updateUser(userId, { role: newRole });
  };

  return (
    <Card className="bg-slate-800/50 border-slate-700">
      <CardHeader>
        <CardTitle className="text-white">Gestion des Utilisateurs</CardTitle>
        <CardDescription className="text-slate-400">
          Gérez les comptes utilisateurs et leurs permissions
        </CardDescription>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="flex items-center space-x-2">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-slate-400" />
            <Input
              placeholder="Rechercher un utilisateur..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10 bg-slate-900/50 border-slate-600 text-white placeholder:text-slate-500"
            />
          </div>
          <Button variant="outline" onClick={refetchUsers} className="border-slate-600">
            Actualiser
          </Button>
        </div>

        <div className="rounded-md border border-slate-700">
          <Table>
            <TableHeader>
              <TableRow className="border-slate-700 hover:bg-slate-800/50">
                <TableHead className="text-slate-300">Email</TableHead>
                <TableHead className="text-slate-300">Nom</TableHead>
                <TableHead className="text-slate-300">Rôle</TableHead>
                <TableHead className="text-slate-300">Plan</TableHead>
                <TableHead className="text-slate-300">Statut</TableHead>
                <TableHead className="text-slate-300">Date création</TableHead>
                <TableHead className="text-slate-300">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {filteredUsers.map((user) => (
                <TableRow key={user.id} className="border-slate-700 hover:bg-slate-800/30">
                  <TableCell className="text-slate-200">{user.email}</TableCell>
                  <TableCell className="text-slate-200">
                    {user.first_name} {user.last_name}
                  </TableCell>
                  <TableCell>
                    <Select
                      value={user.role}
                      onValueChange={(value) => handleRoleChange(user.id, value)}
                    >
                      <SelectTrigger className="w-32 bg-slate-900/50 border-slate-600 text-white">
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent className="bg-slate-900 border-slate-700">
                        <SelectItem value="user" className="text-white">
                          <div className="flex items-center">
                            <User className="h-4 w-4 mr-2" />
                            Utilisateur
                          </div>
                        </SelectItem>
                        <SelectItem value="admin" className="text-white">
                          <div className="flex items-center">
                            <Shield className="h-4 w-4 mr-2" />
                            Admin
                          </div>
                        </SelectItem>
                      </SelectContent>
                    </Select>
                  </TableCell>
                  <TableCell className="text-slate-200">
                    <Badge variant="outline" className="border-blue-500 text-blue-400">
                      {user.subscription_plan}
                    </Badge>
                  </TableCell>
                  <TableCell>
                    {user.is_active ? (
                      <Badge className="bg-green-500/20 text-green-400 border-green-500/50">
                        Actif
                      </Badge>
                    ) : (
                      <Badge variant="destructive">
                        Inactif
                      </Badge>
                    )}
                  </TableCell>
                  <TableCell className="text-slate-400">
                    {new Date(user.created_at).toLocaleDateString('fr-FR')}
                  </TableCell>
                  <TableCell>
                    <div className="flex items-center space-x-2">
                      {user.is_active ? (
                        <Button
                          size="sm"
                          variant="outline"
                          className="border-red-500 text-red-400 hover:bg-red-500/10"
                          onClick={() => {
                            setSelectedUser(user);
                            setActionType('deactivate');
                            setActionDialogOpen(true);
                          }}
                        >
                          <UserX className="h-3 w-3 mr-1" />
                          Désactiver
                        </Button>
                      ) : (
                        <Button
                          size="sm"
                          variant="outline"
                          className="border-green-500 text-green-400 hover:bg-green-500/10"
                          onClick={() => {
                            setSelectedUser(user);
                            setActionType('activate');
                            setActionDialogOpen(true);
                          }}
                        >
                          <UserCheck className="h-3 w-3 mr-1" />
                          Activer
                        </Button>
                      )}
                    </div>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      </CardContent>

      <AlertDialog open={actionDialogOpen} onOpenChange={setActionDialogOpen}>
        <AlertDialogContent className="bg-slate-900 border-slate-700">
          <AlertDialogHeader>
            <AlertDialogTitle className="text-white">
              {actionType === 'activate' ? 'Activer' : 'Désactiver'} l'utilisateur
            </AlertDialogTitle>
            <AlertDialogDescription className="text-slate-400">
              Êtes-vous sûr de vouloir {actionType === 'activate' ? 'activer' : 'désactiver'} le compte de{' '}
              {selectedUser?.email} ?
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="bg-slate-800 border-slate-700 text-white">
              Annuler
            </AlertDialogCancel>
            <AlertDialogAction onClick={handleAction} className="bg-blue-600 hover:bg-blue-700">
              Confirmer
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </Card>
  );
}
