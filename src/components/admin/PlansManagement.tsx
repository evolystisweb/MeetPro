import { useState } from 'react';
import { useAdminData } from '@/hooks/useAdminData';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../ui/card';
import { Button } from '../ui/button';
import { Input } from '../ui/input';
import { Label } from '../ui/label';
import { Badge } from '../ui/badge';
import { Plus, Edit, Eye, EyeOff } from 'lucide-react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '../ui/dialog';
import { Switch } from '../ui/switch';

export function PlansManagement() {
  const { plans, createPlan, updatePlan, refetchPlans } = useAdminData();
  const [dialogOpen, setDialogOpen] = useState(false);
  const [editingPlan, setEditingPlan] = useState<any>(null);
  const [formData, setFormData] = useState({
    name: '',
    price: 0,
    billing_period: 'monthly',
    max_events: 0,
    max_bookings_per_month: 0,
    features: [] as string[],
    is_active: true,
  });

  const handleOpenDialog = (plan?: any) => {
    if (plan) {
      setEditingPlan(plan);
      setFormData({
        name: plan.name,
        price: plan.price,
        billing_period: plan.billing_period,
        max_events: plan.max_events,
        max_bookings_per_month: plan.max_bookings_per_month,
        features: plan.features,
        is_active: plan.is_active,
      });
    } else {
      setEditingPlan(null);
      setFormData({
        name: '',
        price: 0,
        billing_period: 'monthly',
        max_events: 0,
        max_bookings_per_month: 0,
        features: [],
        is_active: true,
      });
    }
    setDialogOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (editingPlan) {
      await updatePlan(editingPlan.id, formData);
    } else {
      await createPlan(formData);
    }
    setDialogOpen(false);
  };

  const togglePlanStatus = async (planId: number, currentStatus: boolean) => {
    await updatePlan(planId, { is_active: !currentStatus });
  };

  return (
    <Card className="bg-slate-800/50 border-slate-700">
      <CardHeader>
        <div className="flex justify-between items-center">
          <div>
            <CardTitle className="text-white">Gestion des Plans Tarifaires</CardTitle>
            <CardDescription className="text-slate-400">
              Créez et modifiez les plans d'abonnement
            </CardDescription>
          </div>
          <Button onClick={() => handleOpenDialog()} className="bg-blue-600 hover:bg-blue-700">
            <Plus className="h-4 w-4 mr-2" />
            Nouveau Plan
          </Button>
        </div>
      </CardHeader>
      <CardContent>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {plans.map((plan) => (
            <Card key={plan.id} className="bg-slate-900/50 border-slate-600">
              <CardHeader>
                <div className="flex justify-between items-start">
                  <div>
                    <CardTitle className="text-white text-xl">{plan.name}</CardTitle>
                    <div className="flex items-baseline mt-2">
                      <span className="text-3xl font-bold text-white">
                        {plan.price}€
                      </span>
                      <span className="text-slate-400 ml-2">
                        /{plan.billing_period === 'monthly' ? 'mois' : 'an'}
                      </span>
                    </div>
                  </div>
                  <Badge
                    variant={plan.is_active ? 'default' : 'secondary'}
                    className={plan.is_active ? 'bg-green-500/20 text-green-400' : ''}
                  >
                    {plan.is_active ? 'Actif' : 'Inactif'}
                  </Badge>
                </div>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="space-y-2">
                  <div className="flex justify-between text-sm">
                    <span className="text-slate-400">Événements max:</span>
                    <span className="text-white font-semibold">
                      {plan.max_events === -1 ? 'Illimité' : plan.max_events}
                    </span>
                  </div>
                  <div className="flex justify-between text-sm">
                    <span className="text-slate-400">Réservations/mois:</span>
                    <span className="text-white font-semibold">
                      {plan.max_bookings_per_month === -1 ? 'Illimité' : plan.max_bookings_per_month}
                    </span>
                  </div>
                </div>

                <div className="space-y-2">
                  <p className="text-sm font-semibold text-slate-300">Fonctionnalités:</p>
                  <ul className="space-y-1">
                    {plan.features.map((feature, index) => (
                      <li key={index} className="text-xs text-slate-400 flex items-start">
                        <span className="text-green-500 mr-2">✓</span>
                        {feature}
                      </li>
                    ))}
                  </ul>
                </div>

                <div className="flex space-x-2 pt-4">
                  <Button
                    size="sm"
                    variant="outline"
                    className="flex-1 border-slate-600"
                    onClick={() => handleOpenDialog(plan)}
                  >
                    <Edit className="h-3 w-3 mr-1" />
                    Modifier
                  </Button>
                  <Button
                    size="sm"
                    variant="outline"
                    className={`border-slate-600 ${
                      plan.is_active ? 'text-red-400' : 'text-green-400'
                    }`}
                    onClick={() => togglePlanStatus(plan.id, plan.is_active)}
                  >
                    {plan.is_active ? (
                      <>
                        <EyeOff className="h-3 w-3 mr-1" />
                        Désactiver
                      </>
                    ) : (
                      <>
                        <Eye className="h-3 w-3 mr-1" />
                        Activer
                      </>
                    )}
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </CardContent>

      <Dialog open={dialogOpen} onOpenChange={setDialogOpen}>
        <DialogContent className="bg-slate-900 border-slate-700 max-w-2xl">
          <DialogHeader>
            <DialogTitle className="text-white">
              {editingPlan ? 'Modifier le plan' : 'Créer un nouveau plan'}
            </DialogTitle>
            <DialogDescription className="text-slate-400">
              Configurez les détails du plan d'abonnement
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="name" className="text-slate-300">Nom du plan</Label>
                <Input
                  id="name"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="bg-slate-800 border-slate-600 text-white"
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="price" className="text-slate-300">Prix (€)</Label>
                <Input
                  id="price"
                  type="number"
                  step="0.01"
                  value={formData.price}
                  onChange={(e) => setFormData({ ...formData, price: parseFloat(e.target.value) })}
                  className="bg-slate-800 border-slate-600 text-white"
                  required
                />
              </div>
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="max_events" className="text-slate-300">Max événements (-1 = illimité)</Label>
                <Input
                  id="max_events"
                  type="number"
                  value={formData.max_events}
                  onChange={(e) => setFormData({ ...formData, max_events: parseInt(e.target.value) })}
                  className="bg-slate-800 border-slate-600 text-white"
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="max_bookings" className="text-slate-300">Max réservations/mois (-1 = illimité)</Label>
                <Input
                  id="max_bookings"
                  type="number"
                  value={formData.max_bookings_per_month}
                  onChange={(e) => setFormData({ ...formData, max_bookings_per_month: parseInt(e.target.value) })}
                  className="bg-slate-800 border-slate-600 text-white"
                  required
                />
              </div>
            </div>

            <div className="flex items-center space-x-2">
              <Switch
                id="is_active"
                checked={formData.is_active}
                onCheckedChange={(checked) => setFormData({ ...formData, is_active: checked })}
              />
              <Label htmlFor="is_active" className="text-slate-300">Plan actif</Label>
            </div>

            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setDialogOpen(false)} className="border-slate-600">
                Annuler
              </Button>
              <Button type="submit" className="bg-blue-600 hover:bg-blue-700">
                {editingPlan ? 'Mettre à jour' : 'Créer'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </Card>
  );
}
