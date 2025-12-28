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
import { Search, Calendar } from 'lucide-react';

export function SubscriptionsManagement() {
  const { subscriptions, updateSubscription, refetchSubscriptions } = useAdminData();
  const [searchTerm, setSearchTerm] = useState('');

  const filteredSubscriptions = subscriptions.filter(sub =>
    sub.user_email?.toLowerCase().includes(searchTerm.toLowerCase()) ||
    sub.plan_name?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleStatusChange = async (subscriptionId: string, newStatus: string) => {
    await updateSubscription(subscriptionId, { status: newStatus });
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'active':
        return <Badge className="bg-green-500/20 text-green-400 border-green-500/50">Actif</Badge>;
      case 'cancelled':
        return <Badge variant="destructive">Annulé</Badge>;
      case 'expired':
        return <Badge className="bg-orange-500/20 text-orange-400 border-orange-500/50">Expiré</Badge>;
      case 'pending':
        return <Badge className="bg-yellow-500/20 text-yellow-400 border-yellow-500/50">En attente</Badge>;
      default:
        return <Badge variant="outline">{status}</Badge>;
    }
  };

  return (
    <Card className="bg-slate-800/50 border-slate-700">
      <CardHeader>
        <CardTitle className="text-white">Gestion des Abonnements</CardTitle>
        <CardDescription className="text-slate-400">
          Gérez les abonnements actifs et historiques
        </CardDescription>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="flex items-center space-x-2">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-slate-400" />
            <Input
              placeholder="Rechercher par email ou plan..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="pl-10 bg-slate-900/50 border-slate-600 text-white placeholder:text-slate-500"
            />
          </div>
          <Button variant="outline" onClick={refetchSubscriptions} className="border-slate-600">
            Actualiser
          </Button>
        </div>

        <div className="rounded-md border border-slate-700">
          <Table>
            <TableHeader>
              <TableRow className="border-slate-700 hover:bg-slate-800/50">
                <TableHead className="text-slate-300">Utilisateur</TableHead>
                <TableHead className="text-slate-300">Plan</TableHead>
                <TableHead className="text-slate-300">Statut</TableHead>
                <TableHead className="text-slate-300">Date début</TableHead>
                <TableHead className="text-slate-300">Date fin</TableHead>
                <TableHead className="text-slate-300">Renouvellement auto</TableHead>
                <TableHead className="text-slate-300">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {filteredSubscriptions.map((subscription) => (
                <TableRow key={subscription.id} className="border-slate-700 hover:bg-slate-800/30">
                  <TableCell className="text-slate-200">{subscription.user_email}</TableCell>
                  <TableCell className="text-slate-200">
                    <Badge variant="outline" className="border-blue-500 text-blue-400">
                      {subscription.plan_name}
                    </Badge>
                  </TableCell>
                  <TableCell>{getStatusBadge(subscription.status)}</TableCell>
                  <TableCell className="text-slate-400">
                    <div className="flex items-center">
                      <Calendar className="h-3 w-3 mr-1" />
                      {new Date(subscription.start_date).toLocaleDateString('fr-FR')}
                    </div>
                  </TableCell>
                  <TableCell className="text-slate-400">
                    <div className="flex items-center">
                      <Calendar className="h-3 w-3 mr-1" />
                      {new Date(subscription.end_date).toLocaleDateString('fr-FR')}
                    </div>
                  </TableCell>
                  <TableCell>
                    {subscription.auto_renew ? (
                      <Badge className="bg-green-500/20 text-green-400 border-green-500/50">
                        Oui
                      </Badge>
                    ) : (
                      <Badge variant="outline" className="border-slate-500 text-slate-400">
                        Non
                      </Badge>
                    )}
                  </TableCell>
                  <TableCell>
                    <Select
                      value={subscription.status}
                      onValueChange={(value) => handleStatusChange(subscription.id, value)}
                    >
                      <SelectTrigger className="w-32 bg-slate-900/50 border-slate-600 text-white">
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent className="bg-slate-900 border-slate-700">
                        <SelectItem value="active" className="text-white">Actif</SelectItem>
                        <SelectItem value="cancelled" className="text-white">Annulé</SelectItem>
                        <SelectItem value="expired" className="text-white">Expiré</SelectItem>
                        <SelectItem value="pending" className="text-white">En attente</SelectItem>
                      </SelectContent>
                    </Select>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>

        <div className="flex items-center justify-between pt-4">
          <p className="text-sm text-slate-400">
            Total: {filteredSubscriptions.length} abonnement(s)
          </p>
        </div>
      </CardContent>
    </Card>
  );
}
