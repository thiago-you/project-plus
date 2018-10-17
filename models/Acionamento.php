<?php

namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "acionamento".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_contrato
 * @property int $colaborador_id
 * @property string $titulo
 * @property string $descricao
 * @property string $data
 * @property string $hora
 * @property string $telefone
 * @property int $tipo Flag que valida o tipo do evento
 * @property int $subtipo
 *
 * @property Cliente $cliente
 * @property Colaborador $colaborador
 * @property Contrato $contrato
 */
class Acionamento extends \yii\db\ActiveRecord
{
    // const para o tipo de acionamento do sistema
    CONST TIPO_SISTEMA = '0';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acionamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['id_cliente', 'tipo'], 'required'],
            [['id_cliente', 'id_contrato', 'colaborador_id', 'tipo', 'subtipo'], 'integer'],
            [['data', 'hora'], 'safe'],
            [['titulo'], 'string', 'max' => 100],
            [['descricao'], 'string', 'max' => 250],
            [['telefone'], 'string', 'max' => 15],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contrato::className(), 'targetAttribute' => ['id_contrato' => 'id']],
        ];
        
        // valisa se o usuairo logado é admin
        if (\Yii::$app->user->id > 0) {
            $rules[] = [['colaborador_id'], 'required'];
            $rules[] = [['colaborador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colaborador::className(), 'targetAttribute' => ['colaborador_id' => 'id']];
        }
        
        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_cliente' => 'Id Cliente',
            'id_contrato' => 'Id Contrato',
            'colaborador_id' => 'Colaborador ID',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'data' => 'Data',
            'hora' => 'Hora',
            'telefone' => 'Telefone',
            'tipo' => 'Tipo',
            'subtipo' => 'Subtipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contrato::className(), ['id' => 'id_contrato']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColaborador()
    {
        // valida o colaborador
        if ($this->colaborador_id == 0) {
            return Colaborador::getAdminUser();            
        }
        
        return $this->hasOne(Colaborador::className(), ['id' => 'colaborador_id']);
    }
    
    /**
     * Retorna os tipos de acionamento
     */
    public static function getTipos() 
    {
        return [
            '1' => 'Agendamento',
            '2' => 'Contato com o cliente',
            '3' => 'Outros',
        ];        
    }
    
    /**
     * Retorna os tipos de acionamento
     */
    public function getTipo()
    {
        // busca a lista de tipos
        $tipos = Acionamento::getTipos();
 
        // adiciona o tipo de sistema
        $tipos[0] = 'Sistema';
        
        // retorna a descrição do tipo atual
        return $tipos[$this->tipo];
    }
    
    /** 
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        if (!empty($this->data)) {
            $this->data = Helper::dateUnmask($this->data, Helper::DATE_DEFAULT, true);
        } else {
            $this->data = date('Y-m-d H:i:s');
        }
        
        return parent::beforeSave($insert);
    }
    
    /**
     * Seta um acionamento automatico
     */
    public static function setAcionamento($params = []) 
    {
        // cria o novo acionamento
        $acionamento = new Acionamento();
        
        // seta os dados do acionamento
        $acionamento->id_cliente = $params['id_cliente'];
        $acionamento->id_contrato = $params['id_contrato'];
        $acionamento->colaborador_id = \Yii::$app->user->id;
        $acionamento->titulo = $params['titulo'];
        $acionamento->descricao = $params['descricao'];
        $acionamento->tipo = $params['tipo'];
        $acionamento->subtipo = isset($params['subtipo']) ? $params['subtipo'] : 0;
        $acionamento->hora = isset($params['hora']) ? $params['hora'] : '';
        
        // salva o acionamento
        return $acionamento->save();
    }
}









